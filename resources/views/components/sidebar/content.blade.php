@props(['warehouses', 'collapsible'])

<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>



    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        Menu
    </div>

    <x-sidebar.link title="Master Barang" href="{{ route('manager.items.index') }}" :isActive="request()->routeIs('manager.items*')">
        <x-slot name="icon">
            <x-heroicon-o-rectangle-stack class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Distributor" href="{{ route('manager.distributors.index') }}" :isActive="request()->routeIs('manager.distributors*')">
        <x-slot name="icon">
            <x-heroicon-o-user-group class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="Pembelian" :active="request()->routeIs('manager.supplier*') || request()->routeIs('manager.purchase*') || request()->routeIs('manager.outgoingpayment*')">
        <x-slot name="icon">
            <x-icons.supplier class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Pemasok Barang" href="{{ route('manager.supplier.index') }}" :active="request()->routeIs('manager.supplier*')" />

        <x-sidebar.sublink title="Pembelian Barang" href="{{ route('manager.purchase.index') }}" :active="request()->routeIs('manager.purchase*')" />

        <x-sidebar.sublink title="Pembayaran Keluar" href="{{ route('manager.outgoingpayment.index') }}" :active="request()->routeIs('manager.outgoingpayment*')" />
    </x-sidebar.dropdown>

    @role('manager')
        <x-sidebar.link title="Pengguna" href="{{ route('manager.users.index') }}" :isActive="request()->routeIs('manager.users*')">
            <x-slot name="icon">
                <x-heroicon-o-user class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>
    @endrole

    <x-sidebar.dropdown title="Kelola Keuangan" :active="request()->routeIs('manager.finance*')">
        <x-slot name="icon">
            <x-heroicon-o-document-currency-dollar class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Modal Utama" href="{{ route('manager.finance.modal.primaryModal') }}"
            :active="request()->routeIs('manager.finance.modal.primaryModal')" />
    </x-sidebar.dropdown>

    @role('manager|finance')
        <x-sidebar.dropdown title="Finance" :active="request()->routeIs('manager.modal*') || request()->routeIs('manager.asset*')">
            <x-slot name="icon">
                <x-heroicon-o-banknotes class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.sublink title="Kelola Modal" href="{{ route('manager.modal.index') }}" :active="request()->routeIs('manager.modal*')" />

            <x-sidebar.sublink title="Rincian Aset" href="{{ route('manager.asset.index') }}" :active="request()->routeIs('manager.asset*')" />
        </x-sidebar.dropdown>
    @endrole

    @role('manager')
        {{-- Warehouse Start --}}
        <div x-data="{ open: false }">
            <div class="flex items-center">
                <x-sidebar.linkToggle title="Gudang" href="{{ route('manager.warehouses.index') }}" :isActive="request()->routeIs('manager.warehouses*')"
                    :collapsible="true">
                    <x-slot name="icon">
                        <x-icons.warehouse class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
                    </x-slot>
                </x-sidebar.linkToggle>
            </div>

            <div x-show="open && (isSidebarOpen || isSidebarHovered)" x-collapse
                class="relative px-0 pt-2 pb-0 ml-5 before:w-0 before:block before:absolute before:inset-y-0 before:left-0 before:border-l-2 before:border-l-gray-200 dark:before:border-l-gray-600 list-none">
                @foreach ($warehouses as $warehouse)
                    <x-sidebar.sublink title="{{ $warehouse->name }}"
                        href="{{ route('manager.warehouses.show', $warehouse->slug) }}" :active="request()->routeIs('manager.warehouses.show') &&
                            request()->route('warehouse') == $warehouse->slug"
                        class="relative">
                    </x-sidebar.sublink>
                @endforeach
            </div>
        </div>
        {{-- Warehouse End --}}
    @endrole


    <x-sidebar.dropdown title="Penjualan" :active="request()->routeIs('manager.sales*')">
        <x-slot name="icon">
            <x-icons.sale-tag class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Data Penjualan" href="{{ route('manager.sales.index') }}" :active="request()->routeIs('manager.sales.index')" />
        <x-sidebar.sublink title="Pesan Penjualan" href="{{ route('manager.sales.create') }}" :active="request()->routeIs('manager.sales.create')" />
    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="Lain Lain" :active="request()->routeIs('manager.other*')">
        <x-slot name="icon">
            <x-icons.list-bullet class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Kategori" href="{{ route('manager.other.categories.index') }}" :active="request()->routeIs('manager.other.categories*')" />

        <x-sidebar.sublink title="Pelanggan" href="{{ route('manager.other.buyer.index') }}" :active="request()->routeIs('manager.other.buyer*')" />

        <x-sidebar.sublink title="Sales" href="{{ route('manager.other.salesman.index') }}" :active="request()->routeIs('manager.other.salesman*')" />
    </x-sidebar.dropdown>

</x-perfect-scrollbar>
