<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Store') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('stores.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Store Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Store Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Describe your store and what kind of card packs you'll be offering..."></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="banner_url" :value="__('Banner Image URL (Optional)')" />
                            <x-text-input id="banner_url" name="banner_url" type="url" class="mt-1 block w-full" placeholder="https://example.com/banner-image.jpg" />
                            <p class="mt-1 text-sm text-gray-500">{{ __('Provide a URL for your store banner image. Recommended size: 1200x400 pixels.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('banner_url')" />
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Store Guidelines') }}</h3>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                <li>{{ __('Your store must have a clear and appropriate name') }}</li>
                                <li>{{ __('Provide a detailed description to help users understand your store\'s theme') }}</li>
                                <li>{{ __('You can create multiple card packs once your store is set up') }}</li>
                                <li>{{ __('You can change your store\'s status at any time') }}</li>
                                <li>{{ __('Maintain appropriate content and follow community guidelines') }}</li>
                            </ul>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Store') }}</x-primary-button>
                            <a href="{{ route('stores.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
