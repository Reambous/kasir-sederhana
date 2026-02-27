<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->code }}</title>
    <style>
        /* Pengaturan khusus untuk Printer Thermal */
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            /* Font mesin ketik khas struk */
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 10px;
            width: 80mm;
            /* Standar ukuran kertas kasir besar */
            background-color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Garis putus-putus khas struk */
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 2px 0;
        }

        /* Menyembunyikan elemen ini saat benar-benar dicetak ke kertas */
        @media print {
            .no-print {
                display: none;
            }

            body {
                width: 100%;
                padding: 0;
            }
        }

        /* Container agar di layar komputer tetap terlihat rapi di tengah */
        .receipt-container {
            max-width: 80mm;
            margin: 0 auto;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt-container">
        <div class="text-center font-bold" style="font-size: 16px; margin-bottom: 2px;">NAMA TOKO ENTERPRISE</div>
        <div class="text-center" style="font-size: 10px;">Jl. Teknologi Modern No. 99, Jakarta</div>
        <div class="text-center" style="font-size: 10px;">Telp: 0812-3456-7890</div>

        <div class="divider" style="margin-top: 8px;"></div>

        <table style="font-size: 11px;">
            <tr>
                <td width="30%">No</td>
                <td width="5%">:</td>
                <td>{{ $order->code }}</td>
            </tr>
            <tr>
                <td>Tgl</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>:</td>
                <td>{{ $order->user->nama ?? 'Sistem' }}</td>
            </tr>
            @if ($order->nama_buyer)
                <tr>
                    <td>Plgn</td>
                    <td>:</td>
                    <td>{{ $order->nama_buyer }}</td>
                </tr>
            @endif
        </table>

        <div class="divider"></div>

        <table>
            @php
                $subtotal = 0;
            @endphp
            @foreach ($order->items as $item)
                @php
                    $totalHargaBarang = $item->jumlah * $item->harga_jual;
                    $subtotal += $totalHargaBarang;
                @endphp
                <tr>
                    <td colspan="3" class="font-bold">{{ $item->nama }}</td>
                </tr>
                <tr>
                    <td width="20%">{{ $item->jumlah }}x</td>
                    <td width="40%" class="text-right">{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td width="40%" class="text-right">{{ number_format($totalHargaBarang, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <table>
            <tr>
                <td width="50%">Subtotal</td>
                <td width="50%" class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            @if ($order->potongan > 0)
                <tr>
                    <td>Diskon</td>
                    <td class="text-right">-{{ number_format($order->potongan, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="font-bold" style="font-size: 14px;">
                <td style="padding-top: 5px;">TOTAL</td>
                <td class="text-right" style="padding-top: 5px;">
                    {{ number_format($subtotal - $order->potongan, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td style="padding-top: 5px;">Bayar ({{ strtoupper($order->metode_pembayaran) }})</td>
                <td class="text-right" style="padding-top: 5px;">
                    {{ number_format($order->uang_diterima, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right font-bold">
                    {{ number_format(abs($order->uang_diterima - ($subtotal - $order->potongan)), 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider" style="margin-top: 8px;"></div>

        <div class="text-center font-bold" style="margin-top: 10px;">TERIMA KASIH</div>
        <div class="text-center" style="font-size: 10px; margin-top: 2px;">Barang yang sudah dibeli<br>tidak dapat
            dikembalikan.</div>

        <div class="text-center" style="font-size: 9px; margin-top: 15px; color: #555;">
            -- Powered by POSSYS Enterprise --
        </div>

        <div class="no-print text-center" style="margin-top: 20px;">
            <button onclick="window.close()"
                style="padding: 8px 15px; background: #4f46e5; color: white; border: none; border-radius: 4px; cursor: pointer; font-family: sans-serif;">
                Tutup Jendela Ini
            </button>
        </div>
    </div>

</body>

</html>
