<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Modal Utama') }}
        </h2>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __('Modal Utama!') }}
    </div>

    <div class="p-6 mt-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="mb-5 space-y-2">
            <x-form.label for="amount" :value="__('Jumlah Modal Utama')" />
            <x-form.input id="amount" class="block w-full" type="text" name="amount" :value="$primaryModal"
                placeholder="{{ __('Jumlah') }}" required autofocus disabled />
        </div>
    </div>
</x-app-layout>
