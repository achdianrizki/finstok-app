<x-app-layout>
  <x-slot name="header">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <h2 class="text-xl font-semibold leading-tight">
                @section('title', __('Sampah Penjualan Barang'))
              {{ __('Sampah Penjualan Barang') }}
          </h2>
      </div>
  </x-slot>

  <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
      <div class="flex flex-col md:flex-row md:justify-end gap-4 my-3">
          <div class="w-full md:w-auto">
              <input type="text" id="search" placeholder="Search items..."
                  class=" rounded w-full md:w-auto px-4 py-2 dark:bg-dark-eval-1" name="search">
          </div>
      </div>

      <div class="overflow-x-auto">
          <table id="export-table" class="min-w-full rounded-md">
              <thead>
                  <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nomor Penjualan</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Tanggal Penjualan</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Nama Pelanggan</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                  </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1" id="itemTable">
                  @if ($deletedSales->isEmpty())
                      <tr>
                          <td colspan="5" class="px-6 py-4 text-center">
                              Data tidak ditemukan.
                          </td>
                      </tr>
                  @else
                      @foreach ($deletedSales as $sale)
                          <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                              {{-- <td class="px-6 py-4 whitespace-nowrap">
                                  <a href="/manager/sale/{{ $sale->id }}" class="text-blue-500 hover:underline">{{ $sale->purchase_number }}</a>
                              </td> --}}
                              <td class="px-6 py-4 whitespace-nowrap">
                                  {{ $sale->sale_number }}
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $sale->sale_date }}</td>
                              <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $sale->buyer->name }}</td>
                              <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $sale->status === 'belum_lunas' ? 'Belum Lunas' : 'Lunas' }}</td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                  <form action="{{ route('manager.trash.sale.restore', $sale->id) }}" method="POST" class="inline">
                                      @csrf
                                      <x-button type="submit" size="sm" class="text-blue-600 hover:underline">Restore</x-button>
                                  </form>
                                  {{-- <form action="{{ route('manager.buyer.forceDelete', $buyer->id) }}" method="POST" class="inline">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="text-red-600 hover:underline">Hapus Permanen</button>
                                  </form> --}}

                                  {{-- <x-button target="" href="/manager/sale/{{ $sale->id }}" variant="primary" size="sm" class="justify-center max-w-sm gap-2">
                                      Restore
                                  </x-button> --}}

                                  {{-- <form action="{{ route('manager.sale.restore', ['id' => $sale->id]) }}" method="POST" class="inline">
                                      @csrf
                                      @method('PUT')
                                      <button type="submit" class="bg-green-800 text-white p-2 rounded">
                                          Restore
                                      </button>
                                  </form> --}}
                              </td>
                          </tr>
                      @endforeach
                  @endif
              </tbody>
          </table>
      </div>
      


      

  </div>

  @push('scripts')
  <script>
    $(document).ready(function () {
        $('#search').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#itemTable tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
  @endpush

</x-app-layout>
