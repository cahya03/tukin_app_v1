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
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Jenis Pegawai</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="tni_pns" value="TNI" class="form-radio text-blue-600" checked>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">TNI</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="tni_pns" value="PNS" class="form-radio text-blue-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">PNS</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">File</label>
                    <input type="file" name="file" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
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