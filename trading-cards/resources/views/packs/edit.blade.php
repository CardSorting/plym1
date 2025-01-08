<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pack') }}: {{ $pack->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('packs.update', $pack) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="store_id" :value="__('Store')" />
                            <select id="store_id" name="store_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id', $pack->store_id) == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('store_id')" />
                        </div>

                        <div>
                            <x-input-label for="name" :value="__('Pack Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $pack->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Pack Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $pack->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Price ($)')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $pack->price)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="cards_per_pack" :value="__('Cards Per Pack')" />
                            <x-text-input id="cards_per_pack" name="cards_per_pack" type="number" min="1" class="mt-1 block w-full" :value="old('cards_per_pack', $pack->cards_per_pack)" required />
                            <p class="mt-1 text-sm text-gray-500">{{ __('Number of cards that will be randomly selected from the card pool when a user purchases this pack.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('cards_per_pack')" />
                        </div>

                        <div>
                            <label for="is_available" class="inline-flex items-center">
                                <input id="is_available" name="is_available" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('is_available', $pack->is_available) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Pack is available for purchase') }}</span>
                            </label>
                        </div>

                        <div>
                            <x-input-label :value="__('Select Cards for Pack')" />
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($cards as $card)
                                    <label class="relative flex items-start p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="card_pool[]" value="{{ $card->id }}" 
                                            class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ in_array($card->id, old('card_pool', $pack->cards->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full h-32 object-cover rounded mb-2">
                                            <h4 class="font-medium">{{ $card->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($card->description, 50) }}</p>
                                            <span class="inline-block mt-1 px-2 py-1 text-xs rounded {{ 
                                                $card->rarity === 'legendary' ? 'bg-yellow-100 text-yellow-800' :
                                                ($card->rarity === 'rare' ? 'bg-purple-100 text-purple-800' :
                                                ($card->rarity === 'uncommon' ? 'bg-blue-100 text-blue-800' :
                                                'bg-gray-100 text-gray-800'))
                                            }}">
                                                {{ ucfirst($card->rarity) }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('card_pool')" />
                            @if($cards->isEmpty())
                                <p class="mt-2 text-sm text-gray-600">
                                    {{ __('You need to create and publish some cards before you can add them to this pack.') }}
                                    <a href="{{ route('cards.create') }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ __('Create a card') }}
                                    </a>
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Pack') }}</x-primary-button>
                            <a href="{{ route('packs.show', $pack) }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            @if($pack->cards->isEmpty())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Delete Pack') }}</h3>
                        <p class="mb-4 text-sm text-gray-600">{{ __('Once your pack is deleted, all of its resources and data will be permanently deleted.') }}</p>
                        
                        <form method="POST" action="{{ route('packs.destroy', $pack) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this pack?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Delete Pack') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
