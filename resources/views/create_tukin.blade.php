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
        <button @click="$dispatch('open-modal', 'import-tukin-tni')"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Import Tukin TNI
        </button>

        {{-- Tombol export --}}
        <a href="/siswa/export_excel" target="_blank"
            class="ml-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            Export Excel
        </a>
    </div>

    {{-- Komponen Modal --}}
    <x-modal name="import-tukin-tni" :show="false" maxWidth="lg">
        <div class="p-6 bg-dark:bg-gray-800 text-white rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Import File Excel</h2>

            <form method="POST" action="/tukin/create/import_tni" enctype="multipart/form-data">
                @csrf

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Pilih file Excel
                    (.xlsx)</label>
                <input type="file" name="file" required accept=".xlsx,.xls,.zip"
                    class="w-full border border-gray-300 px-3 py-2 rounded mb-4 focus:outline-none focus:ring focus:border-blue-300">

                <div class="flex justify-end space-x-2">
                    <button x-data type="button" @click="$dispatch('close-modal', 'import-tukin-tni')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>