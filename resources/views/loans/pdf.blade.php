<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Inventaris Sekolah</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h2 { text-align: center; margin-bottom: 5px; text-transform: uppercase; }
        p.subtitle { text-align: center; color: #666; margin-top: 0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        td.center { text-align: center; }
    </style>
</head>
<body onload="window.print()">

    <h2>Laporan Peminjaman Inventaris Sekolah</h2>
    <p class="subtitle">Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: center;">Tgl Pinjam</th>
                <th style="text-align: center;">Batas Kembali</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Kondisi Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $loan->user->name ?? '-' }}</td>
                <td>{{ $loan->item->name ?? '-' }} ({{ $loan->item->code ?? '-' }})</td>
                <td class="center">{{ $loan->quantity }} Unit</td>
                <td class="center">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                <td class="center uppercase"><strong>{{ $loan->status }}</strong></td>
                <td class="center">
                    @if($loan->returnLog)
                        {{ ucfirst($loan->returnLog->condition_on_return) }}
                    @else
                        Belum Kembali
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="center" style="color: #666; padding: 20px;">Tidak ada data peminjaman yang ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>