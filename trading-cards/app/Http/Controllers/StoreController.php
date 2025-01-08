<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stores = Auth::user()->stores()->withCount('packs')->latest()->paginate(10);
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'banner_url' => 'nullable|url'
        ]);

        $store = Auth::user()->stores()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'banner_url' => $validated['banner_url'] ?? null,
            'is_active' => true,
            'balance' => 0
        ]);

        return redirect()->route('stores.show', $store)
            ->with('success', 'Store created successfully!');
    }

    public function show(Store $store)
    {
        $this->authorize('view', $store);
        
        $store->load(['packs' => function ($query) {
            $query->where('is_available', true)
                  ->with(['cards' => function ($q) {
                      $q->select('cards.id', 'name', 'image_url', 'rarity');
                  }]);
        }]);

        return view('stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        $this->authorize('update', $store);
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $this->authorize('update', $store);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'banner_url' => 'nullable|url',
            'is_active' => 'boolean'
        ]);

        $store->update($validated);

        return redirect()->route('stores.show', $store)
            ->with('success', 'Store updated successfully!');
    }

    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);
        
        if ($store->balance > 0) {
            return back()->withErrors(['error' => 'Cannot delete store with remaining balance.']);
        }

        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully!');
    }

    public function toggleStatus(Store $store)
    {
        $this->authorize('update', $store);
        
        $store->update(['is_active' => !$store->is_active]);

        return redirect()->route('stores.show', $store)
            ->with('success', 'Store status updated successfully!');
    }

    public function marketplace()
    {
        $stores = Store::where('is_active', true)
            ->withCount('packs')
            ->with(['packs' => function ($query) {
                $query->where('is_available', true)
                      ->take(5);
            }])
            ->paginate(12);

        return view('stores.marketplace', compact('stores'));
    }

    public function withdrawBalance(Store $store)
    {
        $this->authorize('update', $store);

        if ($store->balance <= 0) {
            return back()->withErrors(['error' => 'No balance available to withdraw.']);
        }

        // Here you would implement the withdrawal logic
        // This could include:
        // 1. Process the withdrawal
        // 2. Update store balance
        // 3. Create transaction record

        return redirect()->route('stores.show', $store)
            ->with('success', 'Balance withdrawn successfully!');
    }
}
