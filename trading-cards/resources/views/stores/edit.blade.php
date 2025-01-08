<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Store') }}: {{ $store->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Current Banner Preview -->
                    <div class="mb-6">
                        <div class="relative w-full h-48">
                            @if($store->banner_url)
                                <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg shadow-md flex items-center justify-center">
                                    <p class="text-white text-lg">{{ __('No Banner Image') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('stores.update', $store) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Store Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $store->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Store Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $store->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="banner_url" :value="__('Banner Image URL')" />
                            <x-text-input id="banner_url" name="banner_url" type="url" class="mt-1 block w-full" :value="old('banner_url', $store->banner_url)" placeholder="https://example.com/banner-image.jpg" />
                            <p class="mt-1 text-sm text-gray-500">{{ __('Provide a URL for your store banner image. Recommended size: 1200x400 pixels.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('banner_url')" />
                        </div>

                        <div>
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('is_active', $store->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Store is active and visible in the marketplace') }}</span>
                            </label>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Store Status') }}</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p>{{ __('Active stores:') }}</p>
                                <ul class="list-disc list-inside ml-4">
                                    <li>{{ __('Are visible in the marketplace') }}</li>
                                    <li>{{ __('Can create and sell card packs') }}</li>
                                    <li>{{ __('Can receive payments from sales') }}</li>
                                </ul>
                                <p>{{ __('Inactive stores:') }}</p>
                                <ul class="list-disc list-inside ml-4">
                                    <li>{{ __('Are hidden from the marketplace') }}</li>
                                    <li>{{ __('Cannot sell new packs') }}</li>
                                    <li>{{ __('Can still manage existing packs and withdraw balance') }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Store') }}</x-primary-button>
                            <a href="{{ route('stores.show', $store) }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            @if($store->packs->isEmpty())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Delete Store') }}</h3>
                        <p class="mb-4 text-sm text-gray-600">{{ __('Once your store is deleted, all of its resources and data will be permanently deleted.') }}</p>
                        
                        <form method="POST" action="{{ route('stores.destroy', $store) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this store?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Delete Store') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
