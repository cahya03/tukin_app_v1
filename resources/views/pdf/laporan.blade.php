<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Pengarsipan Tukin per Satker</h2>
    <p>Dicetak: {{ now()->translatedFormat('d F Y') }}</p>

    @foreach($data as $kode_satker => $rekap)
        <h4>Satker: {{ $rekap->first()->nama_satker }} ({{ $kode_satker }})</h4>
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Jumlah Personel</th>
                    <th>Total Tukin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::create()->month($row->bulan)->translatedFormat('F') }}</td>
                        <td>{{ $row->jumlah_personel }}</td>
                        <td>Rp {{ number_format($row->total_tukin, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
