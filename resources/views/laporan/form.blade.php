<form action="{{ route('laporan.pdf') }}" method="GET">
    <label>Dari Bulan:</label>
    <input type="month" name="bulan_awal" required>

    <label>Sampai Bulan:</label>
    <input type="month" name="bulan_akhir" required>

    <button type="submit">Cetak PDF</button>
</form>
