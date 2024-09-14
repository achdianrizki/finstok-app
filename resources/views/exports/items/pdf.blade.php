<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 14px;
        font-family: Arial, sans-serif;
    }

    thead th {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        text-align: left;
        border-bottom: 2px solid #ddd;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    td, th {
        border: 1px solid #dddddd;
    }

    td {
        padding: 10px;
    }

    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
    }

    .no-category {
        color: #ff0000;
        font-weight: bold;
    }

</style>

<table>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Kode</th>
            <th>Stok</th>
            <th>Harga/pcs</th>
            <th>Kategori</th>
            <th>Gudang</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->code }}</td>
            <td>{{ $item->stok }}</td>
            <td>{{ $item->price }}</td>
            <td class="{{ $item->category ? '' : 'no-category' }}">{{ $item->category ? $item->category->name : 'No Category' }}</td>
            <td class="{{ $item->warehouse ? '' : 'no-category' }}">{{ $item->warehouse ? $item->warehouse->name : 'No Warehouse' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
