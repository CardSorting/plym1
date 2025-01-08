<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Services\GoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CardController extends Controller
{
    public function __construct(
        private readonly GoApiService $goApiService
    ) {}

    public function index()
    {
        $cards = Auth::user()->cards()->latest()->paginate(12);
        return view('cards.index', compact('cards'));
    }

    public function create()
    {
        return view('cards.create');
    }

    public function generateImages(Request $request)
    {
        try {
            $validated = $request->validate([
                'prompt' => 'required|string|max:1000',
            ]);

            $imageUrls = $this->goApiService->generateImages($validated['prompt']);

            return response()->json(['image_urls' => $imageUrls]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'prompt' => 'required|string|max:1000',
            'selected_image_url' => 'required|url',
            'card_type' => 'nullable|string|max:255',
            'abilities' => 'nullable|string',
            'flavor_text' => 'nullable|string',
            'power_toughness' => 'nullable|string|max:255',
            'mana_cost' => 'nullable|array',
            'mana_cost.*' => 'string|in:W,U,B,R,G,C'
        ]);

        try {
            // Generate random rarity and card number
            $rarity = Card::generateRandomRarity();
            $cardNumber = Card::generateCardNumber();

            // Create card with selected image and additional fields
            $card = Auth::user()->cards()->create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'image_url' => $validated['selected_image_url'],
                'rarity' => $rarity,
                'is_published' => false,
                'card_type' => $validated['card_type'] ?? 'Creature',
                'abilities' => $validated['abilities'],
                'flavor_text' => $validated['flavor_text'],
                'power_toughness' => $validated['power_toughness'],
                'mana_cost' => $validated['mana_cost'] ?? [],
                'set_name' => 'AI',
                'card_number' => $cardNumber
            ]);

            return redirect()->route('cards.show', $card)
                ->with('success', 'Card created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create card: ' . $e->getMessage()]);
        }
    }

    public function show(Card $card)
    {
        $this->authorize('view', $card);
        return view('cards.show', compact('card'));
    }

    public function edit(Card $card)
    {
        $this->authorize('update', $card);
        return view('cards.edit', compact('card'));
    }

    public function update(Request $request, Card $card)
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'rarity' => 'required|in:common,uncommon,rare,legendary',
            'card_type' => 'nullable|string|max:255',
            'abilities' => 'nullable|string',
            'flavor_text' => 'nullable|string',
            'power_toughness' => 'nullable|string|max:255',
            'mana_cost' => 'nullable|array',
            'mana_cost.*' => 'string|in:W,U,B,R,G,C',
            'is_published' => 'boolean'
        ]);

        // Ensure mana_cost is an array even if no checkboxes are selected
        $validated['mana_cost'] = $validated['mana_cost'] ?? [];

        $card->update($validated);

        return redirect()->route('cards.show', $card)
            ->with('success', 'Card updated successfully!');
    }

    public function destroy(Card $card)
    {
        $this->authorize('delete', $card);
        
        $card->delete();

        return redirect()->route('cards.index')
            ->with('success', 'Card deleted successfully!');
    }

    public function publish(Card $card)
    {
        $this->authorize('update', $card);
        
        $card->update(['is_published' => true]);

        return redirect()->route('cards.show', $card)
            ->with('success', 'Card published successfully!');
    }

    public function unpublish(Card $card)
    {
        $this->authorize('update', $card);
        
        $card->update(['is_published' => false]);

        return redirect()->route('cards.show', $card)
            ->with('success', 'Card unpublished successfully!');
    }
}
