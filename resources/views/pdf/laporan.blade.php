<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div style="text-align: left; width: 100%; margin: 0 auto; padding-bottom: 12px;">
        <p style="margin:0; text-transform: uppercase; font-size: 11pt;">
              MARKAS BESAR TNI ANGKATAN UDARA
        </p>
        <p style="margin:0; text-transform: uppercase; font-size: 11pt; border-bottom: 1px solid black; padding-left: 1px ;padding-bottom: 2px; display: inline-block;"">
            DINAS INFORMASI DAN PENGOLAHAN DATA
        </p>
    </div>
    <h2 style="text-align: center;">Laporan Pengarsipan Tukin per Satker</h2>
    <p>Dicetak: {{ now()->translatedFormat('d F Y') }}</p>

    @foreach($data as $kode_satker => $rekap)
        <h4>Satker: {{ $rekap->first()->nama_satker }} ({{ $kode_satker }})</h4>
        <table>
            <thead>
                <tr>
                    <th>Bulan, Tahun</th>
                    <th>Jumlah Personel</th>
                    <th>Total Tukin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::create($row->tahun, $row->bulan)->translatedFormat('F Y') }}</td>
                        <td>{{ $row->jumlah_personel }}</td>
                        <td>Rp {{ number_format($row->total_tukin, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>