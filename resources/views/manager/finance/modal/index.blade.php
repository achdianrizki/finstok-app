<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Modal') }}
        </h2>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're in page Modal!") }}
    </div>

    <x-table.table>
        <x-slot name="header">
            <x-table.th class="px-16">Jumlah</x-table.th>
            <x-table.th class="px-16">Status</x-table.th>
            <x-table.th>Dibuat Pada</x-table.th>
            <x-table.th class="px-16">Aksi</x-table.th>
        </x-slot>

        @foreach ($modals as $modal)
            <x-table.tr>
                <x-table.td>
                    <span>{{ 'Rp. ' . number_format($modal->amount, 2) }}</span>
                </x-table.td>

                <x-table.td class="px-16">
                    @if ($modal->is_confirm)
                        <x-button variant="success" class="justify-center max-w-xl gap-2" disabled>
                            <x-heroicon-o-check class="w-6 h-6" aria-hidden="true" />
                            <span>Approved</span>
                        </x-button>
                    @else
                        <form action="{{ route('manager.modal.updateStatus', $modal->id) }}" method="POST"
                            class="inline-block">
                            @csrf
                            @method('PUT')
                            <x-button type="submit" variant="warning" class="justify-center max-w-xl gap-2">
                                <x-heroicon-o-check class="w-6 h-6" aria-hidden="true" />
                                <span>Approve</span>
                            </x-button>
                        </form>
                    @endif
                </x-table.td>

                <x-table.td>
                    {{ $modal->created_at->format('d M Y') }}
                </x-table.td>

                <x-table.td>
                    <form action="{{ route('manager.modal.destroy', $modal->id) }}" method="POST"
                        class="inline-block ml-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">
                            Delete
                        </button>
                    </form>
                </x-table.td>
            </x-table.tr>
        @endforeach
    </x-table.table>
</x-app-layout>
