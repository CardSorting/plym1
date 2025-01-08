<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Card') }}: {{ $card->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card Preview -->
                        <div class="flex justify-center">
                            <div id="card-container" class="mtg-card w-[375px] h-[525px] relative text-black rounded-[18px] shadow-lg overflow-hidden transition-transform transform hover:scale-105 duration-500">
                                <div class="card-frame h-full p-3 flex flex-col bg-gradient-to-br from-gray-100 to-gray-200">
                                    <!-- Header: Card Name and Mana Cost -->
                                    <div class="card-header flex justify-between items-center bg-gradient-to-r from-gray-200 to-gray-100 p-2 rounded-t-md mb-1">
                                        <h2 class="card-name text-xl font-bold text-shadow" id="preview-name">{{ $card->name }}</h2>
                                        <div class="mana-cost flex space-x-1" id="preview-mana-cost">
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
                                    <div class="card-type bg-gradient-to-r from-gray-200 to-gray-100 p-2 text-sm border-b border-black border-opacity-20 mb-1" id="preview-type">
                                        {{ $card->card_type ?? 'Creature' }}
                                    </div>

                                    <!-- Card Text: Abilities and Flavor Text -->
                                    <div class="card-text bg-gray-100 bg-opacity-90 p-3 rounded flex-grow overflow-y-auto text-sm leading-relaxed">
                                        <p class="abilities-text mb-2" id="preview-abilities">{{ $card->abilities }}</p>
                                        <p class="flavor-text mt-2 italic" id="preview-flavor-text">{{ $card->flavor_text }}</p>
                                    </div>

                                    <!-- Footer: Rarity and Power/Toughness -->
                                    <div class="card-footer flex justify-between items-center text-white text-xs mt-1 bg-black bg-opacity-50 p-2 rounded-b-md">
                                        <span class="rarity-details">{{ ucfirst($card->rarity) }} ({{ $card->set_name }}-{{ $card->card_number }})</span>
                                        <span class="power-toughness" id="preview-power-toughness">{{ $card->power_toughness }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Form -->
                        <div class="space-y-6">
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
                                <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $card->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-input-label for="card_type" :value="__('Card Type')" />
                                <x-text-input id="card_type" name="card_type" type="text" class="mt-1 block w-full" :value="old('card_type', $card->card_type)" placeholder="e.g., Creature, Artifact, Enchantment" />
                                <x-input-error class="mt-2" :messages="$errors->get('card_type')" />
                            </div>

                            <div>
                                <x-input-label for="abilities" :value="__('Abilities')" />
                                <textarea id="abilities" name="abilities" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Card abilities and effects...">{{ old('abilities', $card->abilities) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('abilities')" />
                            </div>

                            <div>
                                <x-input-label for="flavor_text" :value="__('Flavor Text')" />
                                <textarea id="flavor_text" name="flavor_text" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Optional flavor text...">{{ old('flavor_text', $card->flavor_text) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('flavor_text')" />
                            </div>

                            <div>
                                <x-input-label for="power_toughness" :value="__('Power/Toughness')" />
                                <x-text-input id="power_toughness" name="power_toughness" type="text" class="mt-1 block w-full" :value="old('power_toughness', $card->power_toughness)" placeholder="e.g., 2/3" />
                                <x-input-error class="mt-2" :messages="$errors->get('power_toughness')" />
                            </div>

                            <div>
                                <x-input-label for="mana_cost" :value="__('Mana Cost')" />
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach(['W', 'U', 'B', 'R', 'G'] as $color)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="mana_cost[]" value="{{ $color }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ in_array($color, old('mana_cost', $card->mana_cost ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $color }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('mana_cost')" />
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
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updatePreview = () => {
                document.getElementById('preview-name').textContent = 
                    document.getElementById('name').value || 'Card Name';
                
                document.getElementById('preview-type').textContent = 
                    document.getElementById('card_type').value || 'Creature';
                
                document.getElementById('preview-abilities').textContent = 
                    document.getElementById('abilities').value || '';
                
                document.getElementById('preview-flavor-text').textContent = 
                    document.getElementById('flavor_text').value || '';
                
                document.getElementById('preview-power-toughness').textContent = 
                    document.getElementById('power_toughness').value || '';

                // Update mana cost
                const manaContainer = document.getElementById('preview-mana-cost');
                manaContainer.innerHTML = '';
                document.querySelectorAll('input[name="mana_cost[]"]:checked').forEach(input => {
                    const manaSymbol = document.createElement('div');
                    manaSymbol.className = `mana-symbol rounded-full flex justify-center items-center text-sm font-bold w-6 h-6
                        ${input.value === 'W' ? 'bg-yellow-200 text-black' :
                          input.value === 'U' ? 'bg-blue-500 text-white' :
                          input.value === 'B' ? 'bg-black text-white' :
                          input.value === 'R' ? 'bg-red-500 text-white' :
                          input.value === 'G' ? 'bg-green-500 text-white' :
                          'bg-gray-400 text-black'}`;
                    manaSymbol.textContent = input.value;
                    manaContainer.appendChild(manaSymbol);
                });
            };

            // Add event listeners to all form inputs
            document.querySelectorAll('input, textarea, select').forEach(element => {
                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            });

            // Initial preview update
            updatePreview();
        });
    </script>
    @endpush
</x-app-layout>
