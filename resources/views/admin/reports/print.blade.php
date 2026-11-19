<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan - AD Barbershop Bandung</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0;
            color: #555;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: #f9f9f9;
            padding: 12px 16px;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .sig-col {
            text-align: center;
            width: 200px;
        }
        .sig-line {
            margin-top: 60px;
            border-top: 1px solid #333;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #FFCC00; border: none; font-weight: bold; cursor: pointer;">
            Cetak Dokumen
        </button>
    </div>

    <div class="header">
        <h1>{{ $settings['site_name'] ?? 'AD BARBERSHOP BANDUNG' }}</h1>
        <p>LAPORAN REKAPITULASI PENDAPATAN & TRANSAKSI KAS</p>
        <p style="font-size: 11px;">Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
    </div>

    <div class="summary-box">
        <div>
            <strong>Total Pendapatan Bersih (Lunas):</strong><br>
            <span style="font-size: 16px; font-weight: bold;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        <div>
            <strong>Online (QRIS Snap):</strong><br>
            Rp {{ number_format($onlineRevenue, 0, ',', '.') }}
        </div>
        <div>
            <strong>Tunai (Walk-In):</strong><br>
            Rp {{ number_format($walkInRevenue, 0, ',', '.') }}
        </div>
        <div>
            <strong>Total Transaksi Selesai:</strong><br>
            {{ $paidCount }} Transaksi
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Kapster</th>
                <th>Metode</th>
                <th>Status Bayar</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $idx => $b)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $b->booking_date->format('d/m/Y') }}</td>
                    <td>{{ substr($b->booking_time, 0, 5) }}</td>
                    <td>{{ $b->booking_code }}</td>
                    <td>{{ $b->customer_name }}</td>
                    <td>{{ $b->service->name ?? '-' }}</td>
                    <td>{{ $b->barber->name ?? '-' }}</td>
                    <td>{{ strtoupper($b->payment_method) }}</td>
                    <td>{{ strtoupper($b->payment_status) }}</td>
                    <td class="text-right">{{ number_format($b->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <div class="sig-col">
            <p>Mengetahui,</p>
            <p>Manager Operasional</p>
            <div class="sig-line">AD Barbershop Bandung</div>
        </div>
        <div class="sig-col">
            <p>Bandung, {{ date('d F Y') }}</p>
            <p>Petugas Kasir / Admin</p>
            <div class="sig-line">{{ auth()->user()->name ?? 'Administrator' }}</div>
        </div>
    </div>

</body>
</html>
