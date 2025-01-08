<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Card Packs') }}
            </h2>
            <a href="{{ route('packs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create New Pack') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('packs.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <x-input-label for="search" :value="__('Search')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Search by pack name..." />
                        </div>

                        <div class="w-40">
                            <x-input-label for="store" :value="__('Store')" />
                            <select id="store" name="store" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Stores</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ request('store') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-40">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Status</option>
                                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>

                        <div class="w-40 flex items-end">
                            <x-primary-button>{{ __('Filter') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if($packs->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="mb-4">{{ __('You haven\'t created any card packs yet.') }}</p>
                        <a href="{{ route('packs.create') }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('Create your first pack') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($packs as $pack)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ $pack->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $pack->store->name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded {{ $pack->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $pack->is_available ? __('Available') : __('Unavailable') }}
                                    </span>
                                </div>

                                <p class="text-gray-600 mb-4">{{ Str::limit($pack->description, 100) }}</p>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center p-2 bg-gray-50 rounded">
                                        <span class="block text-sm text-gray-600">{{ __('Price') }}</span>
                                        <span class="block text-lg font-semibold">${{ number_format($pack->price, 2) }}</span>
                                    </div>
                                    <div class="text-center p-2 bg-gray-50 rounded">
                                        <span class="block text-sm text-gray-600">{{ __('Cards Per Pack') }}</span>
                                        <span class="block text-lg font-semibold">{{ $pack->cards_per_pack }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('packs.show', $pack) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                        <a href="{{ route('packs.edit', $pack) }}" class="text-gray-600 hover:text-gray-900">{{ __('Edit') }}</a>
                                    </div>
                                    <form action="{{ route('packs.toggle-availability', $pack) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm {{ $pack->is_available ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                            {{ $pack->is_available ? __('Make Unavailable') : __('Make Available') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $packs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
