<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PackController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $packs = Auth::user()->packs()->with('store')->latest()->paginate(10);
        $stores = Auth::user()->stores;
        return view('packs.index', compact('packs', 'stores'));
    }

    public function create()
    {
        $stores = Auth::user()->stores()->where('is_active', true)->get();
        $cards = Auth::user()->cards()->where('is_published', true)->get();
        return view('packs.create', compact('stores', 'cards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cards_per_pack' => 'required|integer|min:1',
            'card_pool' => 'required|array|min:1',
            'card_pool.*' => 'exists:cards,id'
        ]);

        // Verify store belongs to user
        $store = Store::findOrFail($validated['store_id']);
        if ($store->user_id !== Auth::id()) {
            return back()->withErrors(['store_id' => 'Invalid store selected.']);
        }

        $pack = Auth::user()->packs()->create([
            'store_id' => $validated['store_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'cards_per_pack' => $validated['cards_per_pack'],
            'card_pool' => $validated['card_pool'],
            'is_available' => true
        ]);

        // Attach cards to pack
        $pack->cards()->attach($validated['card_pool']);

        return redirect()->route('packs.show', $pack)
            ->with('success', 'Pack created successfully!');
    }

    public function show(Pack $pack)
    {
        $this->authorize('view', $pack);
        $pack->load(['store', 'cards']);
        return view('packs.show', compact('pack'));
    }

    public function edit(Pack $pack)
    {
        $this->authorize('update', $pack);
        $stores = Auth::user()->stores()->where('is_active', true)->get();
        $cards = Auth::user()->cards()->where('is_published', true)->get();
        return view('packs.edit', compact('pack', 'stores', 'cards'));
    }

    public function update(Request $request, Pack $pack)
    {
        $this->authorize('update', $pack);

        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cards_per_pack' => 'required|integer|min:1',
            'card_pool' => 'required|array|min:1',
            'card_pool.*' => 'exists:cards,id',
            'is_available' => 'boolean'
        ]);

        // Verify store belongs to user
        $store = Store::findOrFail($validated['store_id']);
        if ($store->user_id !== Auth::id()) {
            return back()->withErrors(['store_id' => 'Invalid store selected.']);
        }

        $pack->update($validated);

        // Sync cards
        $pack->cards()->sync($validated['card_pool']);

        return redirect()->route('packs.show', $pack)
            ->with('success', 'Pack updated successfully!');
    }

    public function destroy(Pack $pack)
    {
        $this->authorize('delete', $pack);
        
        $pack->delete();

        return redirect()->route('packs.index')
            ->with('success', 'Pack deleted successfully!');
    }

    public function toggleAvailability(Pack $pack)
    {
        $this->authorize('update', $pack);
        
        $pack->update(['is_available' => !$pack->is_available]);

        return redirect()->route('packs.show', $pack)
            ->with('success', 'Pack availability updated successfully!');
    }

    public function purchase(Pack $pack)
    {
        if (!$pack->is_available) {
            return back()->withErrors(['error' => 'This pack is not available for purchase.']);
        }

        // Here you would implement the purchase logic
        // This could include:
        // 1. Check user balance
        // 2. Process payment
        // 3. Generate random cards from the pack's card pool
        // 4. Add cards to user's collection
        // 5. Update store balance

        return redirect()->route('packs.show', $pack)
            ->with('success', 'Pack purchased successfully!');
    }
}
