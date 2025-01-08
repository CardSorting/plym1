<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Card') }}: {{ $card->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="relative w-48 h-48 mx-auto">
                            <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full h-full object-cover rounded-lg shadow-md">
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
                    </div>

                    <form method="POST" action="{{ route('cards.update', $card) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Card Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $card->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $card->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="rarity" :value="__('Rarity')" />
                            <select id="rarity" name="rarity" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="common" {{ old('rarity', $card->rarity) === 'common' ? 'selected' : '' }}>Common</option>
                                <option value="uncommon" {{ old('rarity', $card->rarity) === 'uncommon' ? 'selected' : '' }}>Uncommon</option>
                                <option value="rare" {{ old('rarity', $card->rarity) === 'rare' ? 'selected' : '' }}>Rare</option>
                                <option value="legendary" {{ old('rarity', $card->rarity) === 'legendary' ? 'selected' : '' }}>Legendary</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('rarity')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Card') }}</x-primary-button>
                            <a href="{{ route('cards.show', $card) }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            @if($card->packs->isNotEmpty())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Card Availability') }}</h3>
                        <div class="space-y-4">
                            <p class="text-sm text-gray-600">{{ __('This card is currently included in the following packs:') }}</p>
                            <div class="space-y-2">
                                @foreach($card->packs as $pack)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="font-medium">{{ $pack->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $pack->store->name }}</div>
                                        </div>
                                        <a href="{{ route('packs.show', $pack) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            {{ __('View Pack') }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-sm text-gray-600">
                                {{ __('Note: Changes to the card will be reflected in all packs where it appears.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
