<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Services\GoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    private $goApiService;

    public function __construct(GoApiService $goApiService)
    {
        $this->goApiService = $goApiService;
        $this->middleware('auth');
    }

    public function index()
    {
        $cards = Auth::user()->cards()->latest()->paginate(12);
        return view('cards.index', compact('cards'));
    }

    public function create()
    {
        return view('cards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'prompt' => 'required|string|max:1000',
            'rarity' => 'required|in:common,uncommon,rare,legendary'
        ]);

        try {
            // Generate image using GoAPI
            $imageUrl = $this->goApiService->generateImage($validated['prompt']);

            // Create card with generated image
            $card = Auth::user()->cards()->create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'image_url' => $imageUrl,
                'rarity' => $validated['rarity'],
                'is_published' => false
            ]);

            return redirect()->route('cards.show', $card)
                ->with('success', 'Card created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to generate card image: ' . $e->getMessage()]);
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
            'is_published' => 'boolean'
        ]);

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
