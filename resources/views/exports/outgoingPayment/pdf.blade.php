<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .company-info {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            /* border: 1px solid black; */
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">Bukti Transfer</div>
    <div class="company-info">
        <strong>Nama Perusahaan</strong><br>
        Alamat Perusahaan, Kota, Negara<br>
        Telp: +62 123 4567 890 | Email: info@perusahaan.com
    </div>

    <table class="table">
        <tr>
            <td>Nomor Pembayaran</td>
            <td>: {{ $outgoingPayment->receipt_number }}</td>
        </tr>
        <tr>
            <td>Tanggal Pembayaran</td>
            <td>: {{ $outgoingPayment->payment_date }}</td>
        </tr>
        <tr>
            <td>Nama Supplier</td>
            <td>: {{ $outgoingPayment->supplier->name }}</td>
        </tr>
        <tr>
            <td>Metode Pembayaran</td>
            <td>: {{ $outgoingPayment->payment_method }}</td>
        </tr>
        <tr>
            <td>Jumlah Transfer</td>
            <td>: Rp{{ number_format($outgoingPayment->amount_paid, 2) }}</td>
        </tr>
        <tr>
            <td>Sisa Pembayaran</td>
            <td>: Rp{{ number_format($outgoingPayment->total_unpaid, 2) }}</td>
        </tr>
        <tr>
            <td>Total Pembelian</td>
            <td>: Rp{{ number_format($outgoingPayment->purchase->total_price, 2) }}</td>
        </tr>
    </table>

    <div class="signature">
        <p>Hormat Kami,</p>
        <br><br>
        <p>(___________________)</p>
    </div>
</body>

</html>
