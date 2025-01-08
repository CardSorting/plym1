<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Card') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Preview -->
                <div class="flex justify-center items-start">
                    <div id="card-container" class="mtg-card w-[375px] h-[525px] relative text-black rounded-[18px] shadow-lg overflow-hidden transition-transform transform hover:scale-105 duration-500">
                        <div class="card-frame h-full p-3 flex flex-col bg-gradient-to-br from-gray-100 to-gray-200">
                            <!-- Header: Card Name and Mana Cost -->
                            <div class="card-header flex justify-between items-center bg-gradient-to-r from-gray-200 to-gray-100 p-2 rounded-t-md mb-1">
                                <h2 class="card-name text-xl font-bold text-shadow" id="preview-name">Card Name</h2>
                                <div class="mana-cost flex space-x-1" id="preview-mana-cost">
                                </div>
                            </div>

                            <!-- Card Image -->
                            <div class="w-full h-[220px] bg-gray-300 rounded mb-1">
                                <img id="preview-image" src="" alt="Selected variation" class="w-full h-full object-cover object-center hidden">
                                <div id="image-placeholder" class="w-full h-full flex items-center justify-center">
                                    <span class="text-gray-600">Image will be generated</span>
                                </div>
                            </div>

                            <!-- Card Type -->
                            <div class="card-type bg-gradient-to-r from-gray-200 to-gray-100 p-2 text-sm border-b border-black border-opacity-20 mb-1" id="preview-type">
                                Creature
                            </div>

                            <!-- Card Text: Abilities and Flavor Text -->
                            <div class="card-text bg-gray-100 bg-opacity-90 p-3 rounded flex-grow overflow-y-auto text-sm leading-relaxed">
                                <p class="abilities-text mb-2" id="preview-abilities"></p>
                                <p class="flavor-text mt-2 italic" id="preview-flavor-text"></p>
                            </div>

                            <!-- Footer: Power/Toughness -->
                            <div class="card-footer flex justify-between items-center text-white text-xs mt-1 bg-black bg-opacity-50 p-2 rounded-b-md">
                                <span class="rarity-details" id="preview-rarity">Rarity will be random</span>
                                <span class="power-toughness" id="preview-power-toughness"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form method="POST" action="{{ route('cards.store') }}" class="space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('Card Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="prompt" :value="__('Image Generation Prompt')" />
                                    <div class="flex gap-2">
                                        <textarea id="prompt" name="prompt" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Describe the image you want to generate..."></textarea>
                                        <button type="button" id="generate-images" class="mt-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            Generate
                                        </button>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Be specific and descriptive for better results.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('prompt')" />
                                </div>

                                <!-- Image Variations Grid -->
                                <div id="image-variations" class="hidden">
                                    <x-input-label for="image_variations" :value="__('Select an Image Variation')" />
                                    <div class="grid grid-cols-2 gap-4 mt-2">
                                        <!-- Image variations will be inserted here -->
                                    </div>
                                </div>

                                <input type="hidden" name="selected_image_url" id="selected_image_url">
                            </div>

                            <div>
                                <x-input-label for="card_type" :value="__('Card Type')" />
                                <x-text-input id="card_type" name="card_type" type="text" class="mt-1 block w-full" placeholder="e.g., Creature, Artifact, Enchantment" />
                                <x-input-error class="mt-2" :messages="$errors->get('card_type')" />
                            </div>

                            <div>
                                <x-input-label for="abilities" :value="__('Abilities')" />
                                <textarea id="abilities" name="abilities" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Card abilities and effects..."></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('abilities')" />
                            </div>

                            <div>
                                <x-input-label for="flavor_text" :value="__('Flavor Text')" />
                                <textarea id="flavor_text" name="flavor_text" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Optional flavor text..."></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('flavor_text')" />
                            </div>

                            <div>
                                <x-input-label for="power_toughness" :value="__('Power/Toughness')" />
                                <x-text-input id="power_toughness" name="power_toughness" type="text" class="mt-1 block w-full" placeholder="e.g., 2/3" />
                                <x-input-error class="mt-2" :messages="$errors->get('power_toughness')" />
                            </div>

                            <div>
                                <x-input-label for="mana_cost" :value="__('Mana Cost')" />
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach(['W', 'U', 'B', 'R', 'G'] as $color)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="mana_cost[]" value="{{ $color }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2">{{ $color }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('mana_cost')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Create Card') }}</x-primary-button>
                                <a href="{{ route('cards.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
            document.querySelectorAll('input, textarea').forEach(element => {
                element.addEventListener('input', updatePreview);
            });

            // Initial preview update
            updatePreview();

            // Handle image generation and selection
            document.getElementById('generate-images').addEventListener('click', async function() {
                const prompt = document.getElementById('prompt').value;
                if (!prompt) {
                    alert('Please enter a prompt first');
                    return;
                }

                this.disabled = true;
                this.textContent = 'Generating...';

                try {
                    // Start the generation task
                    const response = await fetch('{{ route('cards.generate-images') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ prompt })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(`Failed to start generation: ${errorData.error || response.statusText}`);
                    }
                    
                    const { task_id } = await response.json();
                    console.log('Task created:', task_id);

                    // Show loading state
                    const variationsGrid = document.querySelector('#image-variations .grid');
                    variationsGrid.innerHTML = `
                        <div class="col-span-2 text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            <p class="mt-2 text-gray-600">Generating images...</p>
                        </div>
                    `;
                    document.getElementById('image-variations').classList.remove('hidden');

                    // Poll for task completion
                    const pollInterval = 2000; // 2 seconds
                    const pollTask = async () => {
                        const statusResponse = await fetch(`{{ url('cards/tasks') }}/${task_id}`, {
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (!statusResponse.ok) {
                            throw new Error('Failed to check task status');
                        }

                        const statusData = await statusResponse.json();
                        console.log('Task status:', statusData);

                        if (statusData.error) {
                            throw new Error(statusData.error);
                        }

                        if (statusData.status === 'completed') {
                            // Clear loading state and show images
                            variationsGrid.innerHTML = '';
                            statusData.output.image_urls.forEach((url, index) => {
                                const div = document.createElement('div');
                                div.className = 'relative aspect-square cursor-pointer group';
                                div.innerHTML = `
                                    <img src="${url}" alt="Variation ${index + 1}" 
                                        class="w-full h-full object-cover rounded-lg transition-opacity hover:opacity-75">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                        Click to select
                                    </div>
                                `;
                                div.addEventListener('click', () => {
                                    // Update hidden input and preview
                                    document.getElementById('selected_image_url').value = url;
                                    const previewImage = document.getElementById('preview-image');
                                    const placeholder = document.getElementById('image-placeholder');
                                    previewImage.src = url;
                                    previewImage.classList.remove('hidden');
                                    placeholder.classList.add('hidden');

                                    // Update selection UI
                                    variationsGrid.querySelectorAll('.ring-2').forEach(img => 
                                        img.classList.remove('ring-2', 'ring-indigo-500'));
                                    div.classList.add('ring-2', 'ring-indigo-500');
                                });
                                variationsGrid.appendChild(div);
                            });
                            return;
                        } else if (statusData.status === 'failed') {
                            throw new Error(statusData.error || 'Generation failed');
                        }

                        // Continue polling
                        setTimeout(pollTask, pollInterval);
                    };

                    // Start polling
                    setTimeout(pollTask, pollInterval);
                } catch (error) {
                    console.error('Generation error:', error);
                    alert(`Failed to generate images: ${error.message}`);
                } finally {
                    this.disabled = false;
                    this.textContent = 'Generate';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
