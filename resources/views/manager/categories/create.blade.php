<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.other.categories.index') }}" variant="primary" size="sm" class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah kategori') }}
            </h2>
        </div>
    </x-slot> 



    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.other.categories.store') }}" method="POST">
            @csrf

            <div class="grip gap-6">
                <div class="mb-5 space-y-2">
                    <x-form.label for="Nama Kategori" :value="__('Nama Kategori')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name" :value="old('name')"
                        placeholder="{{ __('Nama Kategori') }}" required autofocus />
                </div>

                <div class="grid justify-items-end">
                    <x-button class="gap-2">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>
