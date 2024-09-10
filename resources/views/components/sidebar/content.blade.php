<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>



    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        Menu
    </div>

    <x-sidebar.link title="Kelola barang" href="{{ route('manager.items.index') }}" :isActive="request()->routeIs('manager.items*')">
        <x-slot name="icon">
            <x-heroicon-o-rectangle-stack class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Distributor" href="{{ route('manager.distributors.index') }}" :isActive="request()->routeIs('manager.distributors*')">
        <x-slot name="icon">
            <x-heroicon-o-user-group class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Pengguna" href="{{ route('manager.distributors.index') }}" :isActive="request()->routeIs('manager.distributors*')">
        <x-slot name="icon">
            <x-heroicon-o-user class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Gudang" href="{{ route('manager.warehouse.index') }}" :isActive="request()->routeIs('manager.warehouse*')">
        <x-slot name="icon">
            <x-icons.warehouse class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Kategori" href="{{ route('manager.categories.index') }}" :isActive="request()->routeIs('manager.categories*')">
        <x-slot name="icon">
            <x-icons.category class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="Kelola Keuangan" :active="Str::startsWith(request()->route()->uri(), 'manager')">
        <x-slot name="icon">
            <x-heroicon-o-document-currency-dollar class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Modal Utama" href="{{ route('manager.finance.modal.primaryModal') }}" :active="request()->routeIs('manager.finance.modal.primaryModal')" />
        <x-sidebar.sublink title="Penjualan" href="{{ route('buttons.text') }}" :active="request()->routeIs('buttons.text')" />
        <x-sidebar.sublink title="Pembelian" href="{{ route('buttons.icon') }}" :active="request()->routeIs('buttons.icon')" />
    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="Finance" :active="Str::startsWith(request()->route()->uri(), 'manager')">
        <x-slot name="icon">
            <x-heroicon-o-banknotes class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Kelola Modal" href="{{ route('manager.modal.index') }}" :active="request()->routeIs('manager.modal.index')" />
    </x-sidebar.dropdown>

    {{-- @php
        $links = array_fill(0, 20, '');
    @endphp

    @foreach ($links as $index => $link)
        <x-sidebar.link title="Dummy link {{ $index + 1 }}" href="#" />
    @endforeach --}}

</x-perfect-scrollbar>
