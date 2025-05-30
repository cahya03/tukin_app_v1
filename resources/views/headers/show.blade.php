<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Header: {{ $header->nama_header }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Informasi Header</h3>
                            <div class="space-y-2">
                                <p><span class="font-semibold">Nama Header:</span> {{ $header->nama_header }}</p>
                                <p><span class="font-semibold">Deskripsi:</span> {{ $header->deskripsi_header }}</p>
                                <p><span class="font-semibold">Satker:</span> {{ $header->satker->nama_satker }}</p>
                                <p><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($header->tanggal)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium mb-2">File Terkait</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="font-semibold">File TNI:</p>
                                    @if($header->file_tni_path)
                                        <a href="{{ asset('storage/'.$header->file_tni_path) }}" 
                                           class="text-blue-600 hover:underline flex items-center">
                                           <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                           </svg>
                                           Download File TNI
                                        </a>
                                    @else
                                        <span class="text-red-500">File tidak tersedia</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold">File PNS:</p>
                                    @if($header->file_pns_path)
                                        <a href="{{ asset('storage/'.$header->file_pns_path) }}" 
                                           class="text-blue-600 hover:underline flex items-center">
                                           <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                           </svg>
                                           Download File PNS
                                        </a>
                                    @else
                                        <span class="text-red-500">File tidak tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data TNI -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Data TNI ({{ $tniData->count() }} orang)</h3>
                    @if($tniData->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIP</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No. SK</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bersih</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($tniData as $tni)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $tni->nip }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $tni->nama_pegawai }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $tni->nomor_sk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $tni->grade }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Rp {{ number_format($tni->bersih, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data TNI</p>
                    @endif
                </div>
            </div>

            <!-- Data PNS -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Data PNS ({{ $pnsData->count() }} orang)</h3>
                    @if($pnsData->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Sama seperti tabel TNI -->
                                <!-- ... -->
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data PNS</p>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('header.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                    Kembali ke Daftar Header
                </a>
            </div>
        </div>
    </div>
</x-app-layout>