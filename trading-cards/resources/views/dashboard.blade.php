<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">{{ __('Total Cards') }}</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ Auth::user()->cards->count() }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">{{ __('Active Stores') }}</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ Auth::user()->stores->where('is_active', true)->count() }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">{{ __('Available Packs') }}</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ Auth::user()->packs->where('is_available', true)->count() }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">{{ __('Total Balance') }}</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format(Auth::user()->stores->sum('balance'), 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('cards.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <div>
                                    <div class="font-medium text-gray-900">{{ __('Create Card') }}</div>
                                    <div class="text-sm text-gray-500">{{ __('Design a new trading card') }}</div>
                                </div>
                            </a>

                            <a href="{{ route('stores.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <div>
                                    <div class="font-medium text-gray-900">{{ __('Open Store') }}</div>
                                    <div class="text-sm text-gray-500">{{ __('Start selling card packs') }}</div>
                                </div>
                            </a>

                            <a href="{{ route('packs.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <div>
                                    <div class="font-medium text-gray-900">{{ __('Create Pack') }}</div>
                                    <div class="text-sm text-gray-500">{{ __('Bundle cards into packs') }}</div>
                                </div>
                            </a>

                            <a href="{{ route('marketplace') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <div>
                                    <div class="font-medium text-gray-900">{{ __('Visit Marketplace') }}</div>
                                    <div class="text-sm text-gray-500">{{ __('Browse and buy packs') }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Cards') }}</h3>
                        @if(Auth::user()->cards->isEmpty())
                            <p class="text-gray-600 text-center py-4">{{ __('You haven\'t created any cards yet.') }}</p>
                        @else
                            <div class="space-y-4">
                                @foreach(Auth::user()->cards()->latest()->take(4)->get() as $card)
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-16 h-16 object-cover rounded">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $card->name }}</p>
                                            <p class="text-sm text-gray-500 truncate">{{ Str::limit($card->description, 50) }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                            $card->rarity === 'legendary' ? 'bg-yellow-100 text-yellow-800' :
                                            ($card->rarity === 'rare' ? 'bg-purple-100 text-purple-800' :
                                            ($card->rarity === 'uncommon' ? 'bg-blue-100 text-blue-800' :
                                            'bg-gray-100 text-gray-800'))
                                        }}">
                                            {{ ucfirst($card->rarity) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Store Overview -->
            @if(Auth::user()->stores->isNotEmpty())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Your Stores') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach(Auth::user()->stores as $store)
                                <div class="border rounded-lg overflow-hidden">
                                    @if($store->banner_url)
                                        <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-32 object-cover">
                                    @else
                                        <div class="w-full h-32 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                                    @endif
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $store->name }}</h4>
                                                <p class="text-sm text-gray-500">{{ $store->packs->where('is_available', true)->count() }} {{ __('packs available') }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded {{ $store->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $store->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('stores.show', $store) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                {{ __('View Store') }} →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
