<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice: {{ $order->code }}</title>
    <style>
        body {
            font-family: monospace;
            color: #000;
        }

        .invoice-box {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
        }

        table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 5px 0;
        }

        .border-top {
            border-top: 1px dashed #000;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <h2 class="text-center">TOKO KITA</h2>
        <p class="text-center">Invoice: {{ $order->code }}<br>Tanggal: {{ $order->tanggal }}<br>Kasir:
            {{ $order->user->nama ?? 'Admin' }}</p>

        <table class="border-top">
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->nama }} <br> <small>{{ $item->jumlah }} x Rp
                            {{ number_format($item->harga_jual, 0, ',', '.') }}</small></td>
                    <td class="text-right">Rp {{ number_format($item->harga_jual * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <table class="border-top" style="margin-top: 10px;">
            <tr>
                <th>Total Barang</th>
                <th class="text-right">Rp {{ number_format($total_harga_barang, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <td>Potongan</td>
                <td class="text-right">Rp {{ number_format($order->potongan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Bayar</th>
                <th class="text-right">Rp {{ number_format($total_bayar, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <td>Uang Diterima ({{ strtoupper($order->metode_pembayaran) }})</td>
                <td class="text-right">Rp {{ number_format($order->uang_diterima, 0, ',', '.') }}</td>
            </tr>
            <tr class="border-top">
                <th>Kembalian</th>
                <th class="text-right">Rp {{ number_format($kembalian, 0, ',', '.') }}</th>
            </tr>
        </table>

        <p class="text-center border-top" style="margin-top: 20px; padding-top: 10px;">Terima kasih telah berbelanja!
        </p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
