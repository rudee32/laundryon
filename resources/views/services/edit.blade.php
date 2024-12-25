<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('services.update', $service->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Layanan')" />
                            <x-text-input id="name" class="block mt-1 w-full" 
                                type="text" 
                                name="name" 
                                :value="old('name', $service->name)" 
                                required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="price_per_kg" :value="__('Harga per Kg')" />
                            <x-text-input id="price_per_kg" class="block mt-1 w-full" 
                                type="number" 
                                name="price_per_kg" 
                                :value="old('price_per_kg', $service->price_per_kg)" 
                                required />
                            <x-input-error :messages="$errors->get('price_per_kg')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="estimated_days" :value="__('Estimasi Hari')" />
                            <x-text-input id="estimated_days" class="block mt-1 w-full" 
                                type="number" 
                                name="estimated_days" 
                                :value="old('estimated_days', $service->estimated_days)" 
                                required />
                            <x-input-error :messages="$errors->get('estimated_days')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" 
                                name="description" 
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                rows="3">{{ old('description', $service->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('services.index') }}" class="mr-4">
                                <x-secondary-button type="button">
                                    {{ __('Batal') }}
                                </x-secondary-button>
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 