<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $card->name }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $card)
                    <a href="{{ route('cards.edit', $card) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Edit Card') }}
                    </a>
                    <form action="{{ $card->is_published ? route('cards.unpublish', $card) : route('cards.publish', $card) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 {{ $card->is_published ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ $card->is_published ? __('Unpublish') : __('Publish') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <!-- MTG Style Card -->
                    <div class="flex justify-center">
                        <div class="mtg-card w-[375px] h-[525px] relative text-black rounded-[18px] shadow-lg overflow-hidden transition-transform transform hover:scale-105 duration-500">
                            <div class="card-frame h-full p-3 flex flex-col bg-gradient-to-br from-gray-100 to-gray-200">
                                <!-- Header: Card Name and Mana Cost -->
                                <div class="card-header flex justify-between items-center bg-gradient-to-r from-gray-200 to-gray-100 p-2 rounded-t-md mb-1">
                                    <h2 class="card-name text-xl font-bold text-shadow">{{ $card->name }}</h2>
                                    <div class="mana-cost flex space-x-1">
                                        @foreach($card->mana_cost ?? [] as $symbol)
                                            <div class="mana-symbol rounded-full flex justify-center items-center text-sm font-bold w-6 h-6
                                                {{ $symbol === 'W' ? 'bg-yellow-200 text-black' :
                                                   $symbol === 'U' ? 'bg-blue-500 text-white' :
                                                   $symbol === 'B' ? 'bg-black text-white' :
                                                   $symbol === 'R' ? 'bg-red-500 text-white' :
                                                   $symbol === 'G' ? 'bg-green-500 text-white' :
                                                   'bg-gray-400 text-black' }}">
                                                {{ $symbol }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Card Image -->
                                <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full h-[220px] object-cover object-center rounded mb-1">

                                <!-- Card Type -->
                                <div class="card-type bg-gradient-to-r from-gray-200 to-gray-100 p-2 text-sm border-b border-black border-opacity-20 mb-1">
                                    {{ $card->card_type ?? 'Creature' }}
                                </div>

                                <!-- Card Text: Abilities and Flavor Text -->
                                <div class="card-text bg-gray-100 bg-opacity-90 p-3 rounded flex-grow overflow-y-auto text-sm leading-relaxed">
                                    <p class="abilities-text mb-2">{{ $card->abilities }}</p>
                                    <p class="flavor-text mt-2 italic">{{ $card->flavor_text }}</p>
                                </div>

                                <!-- Footer: Rarity and Power/Toughness -->
                                <div class="card-footer flex justify-between items-center text-white text-xs mt-1 bg-black bg-opacity-50 p-2 rounded-b-md">
                                    <span class="rarity-details">{{ ucfirst($card->rarity) }} ({{ $card->set_name }}-{{ $card->card_number }})</span>
                                    <span class="power-toughness">{{ $card->power_toughness }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Details -->
                    <div class="bg-white rounded-lg shadow p-6 space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Description') }}</h3>
                                <p class="mt-1 text-gray-600">{{ $card->description }}</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Status') }}</h3>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-sm rounded-full {{ $card->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $card->is_published ? __('Published') : __('Unpublished') }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Created By') }}</h3>
                                <p class="mt-1 text-gray-600">{{ $card->user->name }}</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Created At') }}</h3>
                                <p class="mt-1 text-gray-600">{{ $card->created_at->format('F j, Y') }}</p>
                            </div>

                            @if($card->packs->isNotEmpty())
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ __('Available in Packs') }}</h3>
                                    <div class="mt-2 space-y-2">
                                        @foreach($card->packs as $pack)
                                            <a href="{{ route('packs.show', $pack) }}" class="block p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                                <div class="font-medium text-gray-900">{{ $pack->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $pack->store->name }}</div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @can('delete', $card)
                <div class="mt-6 flex justify-end">
                    <form action="{{ route('cards.destroy', $card) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this card?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Delete Card') }}
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
