<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $store->name }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $store)
                    <a href="{{ route('stores.edit', $store) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Edit Store') }}
                    </a>
                    <form action="{{ route('stores.toggle-status', $store) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 {{ $store->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ $store->is_active ? __('Deactivate Store') : __('Activate Store') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Store Banner and Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="relative">
                    @if($store->banner_url)
                        <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                    @endif
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 text-sm font-semibold rounded {{ $store->is_active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                            {{ $store->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">{{ $store->description }}</p>
                    
                    <!-- Store Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="block text-sm text-gray-600">{{ __('Available Packs') }}</span>
                            <span class="block text-2xl font-semibold">{{ $store->packs->where('is_available', true)->count() }}</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="block text-sm text-gray-600">{{ __('Total Sales') }}</span>
                            <span class="block text-2xl font-semibold">0</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="block text-sm text-gray-600">{{ __('Balance') }}</span>
                            <span class="block text-2xl font-semibold">${{ number_format($store->balance, 2) }}</span>
                            @if($store->balance > 0)
                                <form action="{{ route('stores.withdraw', $store) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">
                                        {{ __('Withdraw Funds') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Packs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Card Packs') }}</h3>
                        @can('managePacks', $store)
                            <a href="{{ route('packs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create New Pack') }}
                            </a>
                        @endcan
                    </div>

                    @if($store->packs->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-600">{{ __('No card packs available yet.') }}</p>
                            @can('managePacks', $store)
                                <a href="{{ route('packs.create') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-900">
                                    {{ __('Create your first pack') }}
                                </a>
                            @endcan
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($store->packs as $pack)
                                <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="text-lg font-semibold">{{ $pack->name }}</h4>
                                            <span class="px-2 py-1 text-xs rounded {{ $pack->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $pack->is_available ? __('Available') : __('Unavailable') }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($pack->description, 100) }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold">${{ number_format($pack->price, 2) }}</span>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('packs.show', $pack) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                                @can('update', $pack)
                                                    <a href="{{ route('packs.edit', $pack) }}" class="text-gray-600 hover:text-gray-900">{{ __('Edit') }}</a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
