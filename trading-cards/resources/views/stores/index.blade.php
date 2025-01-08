<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Stores') }}
            </h2>
            <a href="{{ route('stores.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create New Store') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($stores->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="mb-4">{{ __('You haven\'t created any stores yet.') }}</p>
                        <a href="{{ route('stores.create') }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('Create your first store') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stores as $store)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="relative">
                                @if($store->banner_url)
                                    <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                                @endif
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $store->is_active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                                        {{ $store->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2">{{ $store->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($store->description, 100) }}</p>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center p-2 bg-gray-50 rounded">
                                        <span class="block text-sm text-gray-600">{{ __('Packs') }}</span>
                                        <span class="block text-lg font-semibold">{{ $store->packs_count }}</span>
                                    </div>
                                    <div class="text-center p-2 bg-gray-50 rounded">
                                        <span class="block text-sm text-gray-600">{{ __('Balance') }}</span>
                                        <span class="block text-lg font-semibold">${{ number_format($store->balance, 2) }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('stores.show', $store) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                        <a href="{{ route('stores.edit', $store) }}" class="text-gray-600 hover:text-gray-900">{{ __('Edit') }}</a>
                                    </div>
                                    <form action="{{ route('stores.toggle-status', $store) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm {{ $store->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                            {{ $store->is_active ? __('Deactivate') : __('Activate') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $stores->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
