<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.modal.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah Modal') }}
            </h2>
        </div>
    </x-slot>



    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.modal.store') }}" method="POST">
            @csrf

            <div class="grip gap-6">
                <div class="mb-5 space-y-2">
                    <x-form.label for="amount" :value="__('Jumlah Modal')" />
                    <x-form.input id="amount" class="block w-full" type="number" name="amount" :value="old('amount')"
                        placeholder="{{ __('Jumlah Modal') }}" required autofocus />
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
