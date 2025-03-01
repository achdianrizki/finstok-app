<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .table {
            border: 1px solid black;
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-size: 12px;
        }

        .table td {
            font-size: 12px;
        }

        .table-head {
            width: 100%;
            margin-top: 20px;
        }

        .table-head td {
            font-size: 14px;
            padding: 4px 8px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">Pembayaran {{ $purchase->purchase_number }}</div>

    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <div style="width: 45%;">
            <table class="table-head">
                <tr>
                    <td><strong>Pemasok:</strong></td>
                </tr>
                <tr>
                    <td>{{ $purchase->supplier->name }}</td>
                </tr>
                <tr>
                    <td>{{ $purchase->supplier->address }}</td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:0239102931023</td>
                </tr>
                <tr>
                    <td>Bank</td>
                    <td>:0239102931023</td>
                </tr>
                <tr>
                    <td>Telp</td>
                    <td>:{{ $purchase->supplier->phone }}</td>
                </tr>
            </table>
        </div>
        <div style="width: 45%;">
            <table class="table-head">
                <tr>
                    <td><strong>Faktur Pembelian:</strong></td>
                </tr>
                <tr>
                    <td>Nomor Pembelian</td>
                    <td>: {{ $purchase->purchase_number }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pembelian</td>
                    <td>: {{ $purchase->purchase_date }}</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td>: {{ $purchase->supplier->name }}</td>
                </tr>
                <tr>
                    <td>Pajak</td>
                    <td>: {{ number_format($purchase->tax, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: {{ $purchase->status == 'lunas' ? 'Lunas' : 'Belum lunas' }}</td>
                </tr>
            </table>
        </div>
    </div>


    <div class="header">Detail Pembayaran</div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Diskon 1</th>
                <th>Diskon 2</th>
                <th>Diskon 3</th>
                <th>Ad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>{{ $item['unit'] }}</td>
                    <td>{{ number_format($item['purchase_price'], 2, ',', '.') }}</td>
                    <td>{{ $item['discount1'] ?: 0 }}%</td>
                    <td>{{ $item['discount2'] ?: 0 }}%</td>
                    <td>{{ $item['discount3'] ?: 0 }}%</td>
                    <td>{{ $item['ad'] ?: 0 }}</td>
                    <td>{{ number_format($item['price_per_item'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">total</td>
                <td>10</td>
            </tr>
        </tbody>
    </table>

    <table class="table">
        <tr>
            <td>Total Harga Sebelum Pajak</td>
            <td>: {{ number_format($purchase->sub_total, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Pajak</td>
            <td>: {{ number_format($purchase->tax, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Discount 1</td>
            <td>: {{ number_format($purchase->total_discount1, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Discount 2</td>
            <td>: {{ number_format($purchase->total_discount2, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Discount 3</td>
            <td>: {{ number_format($purchase->total_discount3, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Harga Setelah Pajak</td>
            <td>: {{ number_format($purchase->total_price, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div style="display: flex; justify-content: space-between;">
        <div class="signature">
            <p>Diterima Oleh,</p>
            <br><br><br>
            <p>(___________________)</p>
        </div>

        <div class="signature">
            <p>Hormat Kami,</p>
            <br><br><br>
            <p>(___________________)</p>
        </div>
    </div>
</body>

</html>
