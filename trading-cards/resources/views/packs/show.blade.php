<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $pack->name }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $pack)
                    <a href="{{ route('packs.edit', $pack) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Edit Pack') }}
                    </a>
                    <form action="{{ route('packs.toggle-availability', $pack) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 {{ $pack->is_available ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ $pack->is_available ? __('Make Unavailable') : __('Make Available') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Pack Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Pack Details') }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-gray-600">{{ $pack->description }}</p>
                                </div>

                                <div>
                                    <span class="text-sm text-gray-600">{{ __('Store') }}</span>
                                    <p class="font-medium">
                                        <a href="{{ route('stores.show', $pack->store) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $pack->store->name }}
                                        </a>
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <span class="block text-sm text-gray-600">{{ __('Price') }}</span>
                                        <span class="block text-2xl font-semibold">${{ number_format($pack->price, 2) }}</span>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <span class="block text-sm text-gray-600">{{ __('Cards Per Pack') }}</span>
                                        <span class="block text-2xl font-semibold">{{ $pack->cards_per_pack }}</span>
                                    </div>
                                </div>

                                <div>
                                    <span class="text-sm text-gray-600">{{ __('Status') }}</span>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-sm rounded-full {{ $pack->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $pack->is_available ? __('Available') : __('Unavailable') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Purchase Information') }}</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li>• {{ __('Each pack contains') }} {{ $pack->cards_per_pack }} {{ __('randomly selected cards') }}</li>
                                    <li>• {{ __('Cards are selected from the available pool of') }} {{ $pack->cards->count() }} {{ __('cards') }}</li>
                                    <li>• {{ __('Rarity distribution is weighted based on card rarity') }}</li>
                                    <li>• {{ __('Purchased cards are added to your collection') }}</li>
                                </ul>

                                @if($pack->is_available)
                                    @can('purchase', $pack)
                                        <form action="{{ route('packs.purchase', $pack) }}" method="POST" class="mt-4">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Purchase Pack for $:price', ['price' => number_format($pack->price, 2)]) }}
                                            </button>
                                        </form>
                                    @else
                                        <p class="mt-4 text-sm text-gray-600 text-center">
                                            {{ __('You cannot purchase your own packs.') }}
                                        </p>
                                    @endcan
                                @else
                                    <p class="mt-4 text-sm text-gray-600 text-center">
                                        {{ __('This pack is currently unavailable for purchase.') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Cards -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Available Cards') }}</h3>
                    
                    @if($pack->cards->isEmpty())
                        <p class="text-gray-600 text-center py-4">{{ __('No cards available in this pack.') }}</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($pack->cards as $card)
                                <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium">{{ $card->name }}</h4>
                                            <span class="px-2 py-1 text-xs rounded {{ 
                                                $card->rarity === 'legendary' ? 'bg-yellow-100 text-yellow-800' :
                                                ($card->rarity === 'rare' ? 'bg-purple-100 text-purple-800' :
                                                ($card->rarity === 'uncommon' ? 'bg-blue-100 text-blue-800' :
                                                'bg-gray-100 text-gray-800'))
                                            }}">
                                                {{ ucfirst($card->rarity) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ Str::limit($card->description, 100) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            @can('delete', $pack)
                <div class="mt-6 flex justify-end">
                    <form action="{{ route('packs.destroy', $pack) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this pack?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Delete Pack') }}
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
