<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Cards') }}
            </h2>
            <a href="{{ route('cards.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create New Card') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('cards.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <x-input-label for="search" :value="__('Search')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Search by name or description..." />
                        </div>

                        <div class="w-40">
                            <x-input-label for="rarity" :value="__('Rarity')" />
                            <select id="rarity" name="rarity" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Rarities</option>
                                <option value="common" {{ request('rarity') === 'common' ? 'selected' : '' }}>Common</option>
                                <option value="uncommon" {{ request('rarity') === 'uncommon' ? 'selected' : '' }}>Uncommon</option>
                                <option value="rare" {{ request('rarity') === 'rare' ? 'selected' : '' }}>Rare</option>
                                <option value="legendary" {{ request('rarity') === 'legendary' ? 'selected' : '' }}>Legendary</option>
                            </select>
                        </div>

                        <div class="w-40">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="unpublished" {{ request('status') === 'unpublished' ? 'selected' : '' }}>Unpublished</option>
                            </select>
                        </div>

                        <div class="w-40 flex items-end">
                            <x-primary-button>{{ __('Filter') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($cards as $card)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="relative">
                            <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full h-48 object-cover">
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded 
                                    @if($card->rarity === 'legendary') bg-yellow-500 text-white
                                    @elseif($card->rarity === 'rare') bg-purple-500 text-white
                                    @elseif($card->rarity === 'uncommon') bg-blue-500 text-white
                                    @else bg-gray-500 text-white
                                    @endif">
                                    {{ ucfirst($card->rarity) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold">{{ $card->name }}</h3>
                            <p class="text-gray-600 mt-2">{{ Str::limit($card->description, 100) }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <a href="{{ route('cards.show', $card) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    <a href="{{ route('cards.edit', $card) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                                </div>
                                <form action="{{ $card->is_published ? route('cards.unpublish', $card) : route('cards.publish', $card) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm {{ $card->is_published ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                        {{ $card->is_published ? 'Unpublish' : 'Publish' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 text-center">
                            <p class="mb-4">{{ __('No cards found.') }}</p>
                            <a href="{{ route('cards.create') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Create your first card') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $cards->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
