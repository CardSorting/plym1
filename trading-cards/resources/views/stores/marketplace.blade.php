<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trading Card Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('marketplace') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <x-input-label for="search" :value="__('Search Stores')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Search by store name or description..." />
                        </div>

                        <div class="w-40">
                            <x-input-label for="sort" :value="__('Sort By')" />
                            <select id="sort" name="sort" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>

                        <div class="w-40 flex items-end">
                            <x-primary-button>{{ __('Filter') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if($stores->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="mb-4">{{ __('No stores found.') }}</p>
                        @auth
                            <a href="{{ route('stores.create') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Create your own store') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Login to create your own store') }}
                            </a>
                        @endauth
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($stores as $store)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="relative">
                                @if($store->banner_url)
                                    <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-semibold">{{ $store->name }}</h3>
                                        <p class="text-gray-600 mt-1">{{ $store->description }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-sm text-gray-600">{{ __('Available Packs') }}</span>
                                        <span class="block text-lg font-semibold">{{ $store->packs_count }}</span>
                                    </div>
                                </div>

                                @if($store->packs->isNotEmpty())
                                    <div class="mt-6">
                                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Featured Packs') }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($store->packs as $pack)
                                                <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                                    <div class="p-4">
                                                        <h5 class="font-semibold mb-2">{{ $pack->name }}</h5>
                                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($pack->description, 100) }}</p>
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-lg font-bold">${{ number_format($pack->price, 2) }}</span>
                                                            <form action="{{ route('packs.purchase', $pack) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                                    {{ __('Purchase') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-4 text-right">
                                            <a href="{{ route('stores.show', $store) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ __('View All Packs') }} →
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $stores->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
