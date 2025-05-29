<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tukin
        </h2>
    </x-slot>
        <div class="mx-auto mt-10 p-6 bg-white rounded shadow">

        {{-- Notifikasi --}}
        @if ($sukses = Session::get('sukses'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ $sukses }}
            </div>
        @endif

        @if ($errors->has('file'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                {{ $errors->first('file') }}
            </div>
        @endif

        {{-- Tombol untuk membuka modal --}}
        <button onclick="toggleModal(true)"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Import Tukin TNI
        </button>

        {{-- Tombol export --}}
        <a href="/siswa/export_excel" target="_blank"
            class="ml-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            Export Excel
        </a>

        {{-- Modal --}}
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
            <div class="bg-white p-6 rounded shadow-lg max-w-md w-full">
                <h2 class="text-lg font-semibold mb-4">Import File Excel</h2>
                <form method="POST" action="/tukin/import_tni" enctype="multipart/form-data">
                    @csrf
                    <label class="block mb-2 text-sm font-medium text-gray-700">Pilih file Excel (.xlsx)</label>
                    <input type="file" name="file" required accept=".xlsx,.xls,.zip"
                        class="w-full border border-gray-300 px-3 py-2 rounded mb-4 focus:outline-none focus:ring focus:border-blue-300">

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="toggleModal(false)"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 overflow-x-auto">
            <table class="w-full text-xs table-auto border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-1 py-1 border-b">No</th>
                        <th class="text-left px-1 py-1 border-b">Id Tukin</th>
                        <th class="text-left px-1 py-1 border-b">Nomor Tukin</th>
                        <th class="text-left px-1 py-1 border-b">Kode Satker</th>
                        <th class="text-left px-1 py-1 border-b">NIP</th>
                        <th class="text-left px-1 py-1 border-b">Nama Pegawai</th>
                        <th class="text-left px-1 py-1 border-b">Jenis Pegawai</th>
                        <th class="text-left px-1 py-1 border-b">Jenis SK</th>
                        <th class="text-left px-1 py-1 border-b">Nomor SK</th>
                        <th class="text-left px-1 py-1 border-b">Grade</th>
                        <th class="text-left px-1 py-1 border-b">Jenis Tukin</th>
                        <th class="text-left px-1 py-1 border-b">Kotor</th>
                        <th class="text-left px-1 py-1 border-b">Potongan</th>
                        <th class="text-left px-1 py-1 border-b">Bersih</th>
                        <th class="text-left px-1 py-1 border-b">Pajak</th>
                        <th class="text-left px-1 py-1 border-b">Tunjangan Pajak</th>
                        <th class="text-left px-1 py-1 border-b">Bersih 2</th>
                        <th class="text-left px-1 py-1 border-b">Kode Bank</th>
                        <th class="text-left px-1 py-1 border-b">Rekening</th>
                        <th class="text-left px-1 py-1 border-b">Nama Rekening</th>
                        <th class="text-left px-1 py-1 border-b">Nama Bank</th>
                        <th class="text-left px-1 py-1 border-b">Bulan Awal</th>
                        <th class="text-left px-1 py-1 border-b">Tahun Awal</th>
                        <th class="text-left px-1 py-1 border-b">Bulan Akhir</th>
                        <th class="text-left px-1 py-1 border-b">Tahun Akhir</th>
                        <th class="text-left px-1 py-1 border-b">Kali Pembayaran</th>
                        <th class="text-left px-1 py-1 border-b">Nomor Tukin Lama</th>
                        <th class="text-left px-1 py-1 border-b">Nomor Tukin Baru</th>
                        <th class="text-left px-1 py-1 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($tukin as $s)
                        <tr>
                            <td class="px-1 py-1 border-b">{{ $i++ }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->id_tukin }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nomor_tukin }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->kode_satker }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nip }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nama_pegawai }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->jenis_pegawai }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->jenis_sk }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nomor_sk }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->grade }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->jenis_tukin }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->kotor }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->potongan }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->bersih }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->pajak }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->tunjangan_pajak }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->bersih_2 }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->kode_bank }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->rekening }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nama_rekening }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nama_bank }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->bulan_awal }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->tahun_awal }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->bulan_akhir }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->tahun_akhir }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->kali_pembayaran }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nomor_tukin_lama }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->nomor_tukin_baru }}</td>
                            <td class="px-1 py-1 border-b">{{ $s->status }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    {{-- JavaScript modal --}}
    <script>
        function toggleModal(show) {
            const modal = document.getElementById('modal');
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</x-app-layout>
