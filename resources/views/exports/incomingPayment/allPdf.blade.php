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
    <div class="header">Pembayaran {{ $sale->sale_number }}</div>
    <table class="table-head">
        <tr>
            <td>Nomor Penjualan</td>
            <td>: {{ $sale->sale_number }}</td>
        </tr>
        <tr>
            <td>Tanggal Penjualan</td>
            <td>: {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>: {{ $sale->buyer->name }}</td>
        </tr>
        <tr>
            <td>Sales</td>
            <td>: {{ optional($sale->salesman)->name }}</td>
        </tr>
        <tr>
            <td>Pajak</td>
            <td>: Rp {{ number_format($sale->tax, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ $sale->status == 'lunas' ? 'Lunas' : 'Belum lunas' }}</td>
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
        @forelse ($sale->incomingPayments as $incomingPayment)
            <tr>
                <td>{{ $incomingPayment->invoice_number }}</td>
                <td>{{ \Carbon\Carbon::parse($incomingPayment->payment_date)->format('d-m-Y') }}</td>
                <td>{{ $incomingPayment->payment_method }}</td>
                <td>Rp {{ number_format($incomingPayment->pay_amount, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($incomingPayment->remaining_payment, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($incomingPayment->total_paid, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($incomingPayment->sale->total_price, 2, ',', '.') }}</td>
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
