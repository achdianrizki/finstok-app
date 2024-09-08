<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah barang') }}
            </h2>
            <x-button target="" href="#" variant="success" class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Barang</span>
            </x-button>
        </div>
    </x-slot>




    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="" method="POST">
            @csrf

            <div class="grip gap-6">
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Nama Barang')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name"
                        :value="old('name')" placeholder="{{ __('Nama Barang') }}" required autofocus />
                </div>
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Nama Barang')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name"
                        :value="old('name')" placeholder="{{ __('Nama Barang') }}" required autofocus />
                </div>
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Nama Barang')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name"
                        :value="old('name')" placeholder="{{ __('Nama Barang') }}" required autofocus />
                </div>
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Nama Barang')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name"
                        :value="old('name')" placeholder="{{ __('Nama Barang') }}" required autofocus />
                </div>
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Nama Barang')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name"
                        :value="old('name')" placeholder="{{ __('Nama Barang') }}" required autofocus />
                </div>
            </div>
        </form>
    </div>

</x-app-layout>
