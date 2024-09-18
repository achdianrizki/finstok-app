<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Barang') }}
            </h2>
            <x-button target="" href="{{ route('manager.purchase.create') }}" variant="success"
                class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Barang</span>
            </x-button>
        </div>
    </x-slot>


    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're in page Barang!") }}
    </div>

    <x-table.table>
        <x-slot name="header">
            <x-table.th class="px-28">Nama</x-table.th>
            <x-table.th>Stok/pcs</x-table.th>
            <x-table.th>Harga/pcs</x-table.th>
            <x-table.th>Total Harga</x-table.th>
            <x-table.th>Tanggal Masuk Barang</x-table.th>
            <x-table.th class="px-16">Aksi</x-table.th>
        </x-slot>

        {{-- @foreach ($purchase_items as $item) --}}
            <x-table.tr>
                <x-table.td class="px-10 md:px-16">

                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    

                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    
                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    
                </x-table.td>
                <x-table.td>
                    
                </x-table.td>
                <x-table.td>
                    
                </x-table.td>
            </x-table.tr>
        {{-- @endforeach --}}
    </x-table.table>

</x-app-layout>
