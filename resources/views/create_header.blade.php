<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Tukin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if ($sukses = Session::get('sukses'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ $sukses }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tombol untuk membuka modal --}}
            <button @click="$dispatch('open-modal', 'tambah-tukin')"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Tambah Tukin
            </button>
        </div>
    </div>

    {{-- Daftar Header yang Sudah Dibuat --}}
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-semibold mb-4">Daftar Header Tukin</h3>

            @if($headers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Header</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Satker</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jenis Pegawai</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($headers as $header)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $header->nama_header }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        @if($header->satker)
                                            {{ $header->satker->nama_satker }}
                                        @else
                                            <span class="text-red-500">Satker tidak valid</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($header->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        @if($header->file_tni_path)
                                            <a href="{{ asset('storage/'.$header->file_tni_path) }}" class="text-blue-600 hover:underline">TNI</a>
                                        @else
                                            <span class="text-red-500">TNI missing</span>
                                        @endif
                                        /
                                        @if($header->file_pns_path)
                                            <a href="{{ asset('storage/'.$header->file_pns_path) }}" class="text-blue-600 hover:underline">PNS</a>
                                        @else
                                            <span class="text-red-500">PNS missing</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('header.show', $header->id) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Lihat</a>
                                        <a href="{{ route('header.edit', $header->id) }}"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-3">Edit</a>
                                        <form action="{{ route('header.destroy', $header->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $headers->links() }}
                    </div>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">Belum ada header yang dibuat.</p>
            @endif
        </div>
    </div>
    </div>
    </div>


    {{-- Komponen Modal --}}
    <x-modal name="tambah-tukin" :show="false" maxWidth="lg">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Tambah Tukin</h2>

            <form method="POST" action="{{ route('header.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Tukin</label>
                    <input type="text" name="nama_header" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Deskripsi Tukin</label>
                    <input type="text" name="deskripsi_header" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Satker</label>
                    <select name="kode_satker" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                        <option value="">Pilih Satker</option>
                        @foreach(App\Models\Satker::all() as $satker)
                            <option value="{{ $satker->kode_satker }}">{{ $satker->nama_satker }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">File Excel TNI</label>
                    <input type="file" name="file_tni" accept=".xlsx,.xls"
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                    @error('file_tni')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">File Excel PNS</label>
                    <input type="file" name="file_pns" accept=".xlsx,.xls"
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                    @error('file_pns')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button x-data type="button" @click="$dispatch('close-modal', 'tambah-tukin')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>