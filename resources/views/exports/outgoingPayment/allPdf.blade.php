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
        }

        .table {
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
        }

        .table-head {
            width: 280px;
        }

        .table-head td {
            font-size: 14px;
        }

        .table th {
            font-size: 12px;
        }

        .table td {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">Pembayaran {{ $purchase->purchase_number }}</div>
    <table class="table-head">
        <tr>
            <td>Nomor Pembelian</td>
            <td>: {{ $purchase->purchase_number }}</td>
        </tr>
        <tr>
            <td>Tanggal Pembelian</td>
            <td>: {{ $purchase->purchase_date }}</td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td>: {{ $purchase->supplier->name }}</td>
        </tr>
        <tr>
            <td>Sales</td>
            <td>: {{ optional($purchase->salesman)->name }}</td>
        </tr>
        <tr>
            <td>Pajak</td>
            <td>: Rp {{ number_format($purchase->tax, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ $purchase->status == 'lunas' ? 'Lunas' : 'Belum lunas' }}</td>
        </tr>
    </table>

    <div class="header">Detail Pembayaran</div>
    <table class="table">
        <tr>
            <th>Nomor Resi</th>
            <th>Tanggal Pembayaran</th>
            <th>Metode Pembayaran</th>
            <th>Jumlah Dibayarkan</th>
            <th>Sisa Pembayaran</th>
            <th>Total Pembayaran</th>
            <th>Total Harga</th>
        </tr>
        @forelse ($purchase->outgoingPayments as $outgoingPayment)
            <tr>
                <td>{{ $outgoingPayment->receipt_number }}</td>
                <td>{{ $outgoingPayment->payment_date }}</td>
                <td>{{ $outgoingPayment->payment_method }}</td>
                <td>Rp {{ number_format($outgoingPayment->amount_paid, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($outgoingPayment->total_unpaid, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($outgoingPayment->total_paid, 2) }}</td>
                <td>Rp {{ number_format($outgoingPayment->purchase->total_price, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="font-weight: bold; font-style: italic; text-align: center;">Tidak ada data
                    pembayaran</td>
            </tr>
        @endforelse
    </table>
</body>

</html>
