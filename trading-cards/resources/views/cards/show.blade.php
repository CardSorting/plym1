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
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card Image -->
                        <div class="relative">
                            <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full rounded-lg shadow-lg">
                            <div class="absolute top-2 right-2">
                                <span class="px-3 py-1 text-sm font-semibold rounded 
                                    @if($card->rarity === 'legendary') bg-yellow-500 text-white
                                    @elseif($card->rarity === 'rare') bg-purple-500 text-white
                                    @elseif($card->rarity === 'uncommon') bg-blue-500 text-white
                                    @else bg-gray-500 text-white
                                    @endif">
                                    {{ ucfirst($card->rarity) }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div class="space-y-6">
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
