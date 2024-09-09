<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah kategori') }}
            </h2>
            <x-button target="" href="#" variant="success" class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Kategori</span>
            </x-button>
        </div>
    </x-slot>




    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.categories.store') }}" method="POST">
            @csrf

            <div class="grip gap-6">
                <div class="space-y-2">
                    <x-form.label for="Nama Kategori" :value="__('Nama Kategori')" />
                    <x-form.input id="nama_kategori" class="block w-full" type="text" name="nama_kategori" :value="old('nama_kategori')"
                        placeholder="{{ __('Nama Kategori') }}" required autofocus />
                </div>
                <div class="mt-3">
                    <x-button type="submit" variant="success" class="justify-center max-w-xl gap-2">
                        <x-heroicon-o-paper-airplane class="w-6 h-6" aria-hidden="true" />
                        <span>Submit</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

</x-app-layout>
