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

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('manager.modal.store') }}" method="POST">
            @csrf

            <div class="grip gap-6">
                <div class="mb-5 space-y-2">
                    <x-form.label for="amount" :value="__('Jumlah Modal')" />
                    <x-form.input id="modal-amount" class="block w-full" type="text" name="amount" :value="old('amount')"
                        placeholder="{{ __('Masukkan Jumlah Modal') }}" required autofocus />
                </div>

                <div class="grid justify-items-end">
                    <x-button class="gap-2">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('modal-amount').addEventListener('input', function (e) {
            // Hapus semua karakter selain angka
            let value = e.target.value.replace(/[^,\d]/g, '').toString();
            
            // Pisahkan nilai sebelum dan sesudah koma (jika ada)
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/g);
            
            // Tambahkan titik sebagai pemisah ribuan
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            // Jika ada nilai desimal, tambahkan koma
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            
            // Setel nilai yang diformat ke input field
            e.target.value = rupiah;
        });
    </script>

</x-app-layout>
