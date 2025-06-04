<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Header: {{ $header->nama_header }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Authorization Check -->
            @can('view', $header)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium mb-2">Informasi Header</h3>
                                <div class="space-y-2">
                                    <p><span class="font-semibold">Nama Header:</span> {{ $header->nama_header }}</p>
                                    <p><span class="font-semibold">Deskripsi:</span> {{ $header->deskripsi_header }}</p>
                                    <p><span class="font-semibold">Satker:</span> {{ $header->satker->nama_satker }}</p>
                                    <p><span class="font-semibold">Tanggal:</span>
                                        {{ \Carbon\Carbon::parse($header->tanggal)->format('d/m/Y') }}</p>
                                    <p><span class="font-semibold">Dibuat oleh:</span>
                                        {{ $header->creator->name ?? 'Tidak diketahui' }}</p>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium mb-2">File Terkait</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="font-semibold">File TNI:</p>
                                        @if($header->file_tni_path)
                                            <a href="{{ asset('storage/' . $header->file_tni_path) }}"
                                                class="text-blue-600 hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 16v-8m0 8l-3-3m3 3l3-3m4 6H7.5a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 017.5 4.5h9a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0116.5 18z" />
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
                                            <a href="{{ asset('storage/' . $header->file_pns_path) }}"
                                                class="text-blue-600 hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 16v-8m0 8l-3-3m3 3l3-3m4 6H7.5a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 017.5 4.5h9a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0116.5 18z" />
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

                <!-- Statistics -->
                <div class="space-y-4 text-white mb-6">
                    <h3 class="text-lg font-semibold border-b pb-2">Statistik</h3>
                    <p><span class="font-medium">Total Data:</span> {{ $header->tukins->count() }}</p>
                    <p><span class="font-medium">TNI:</span> {{ $tniData->count() }}</p>
                    <p><span class="font-medium">PNS:</span> {{ $pnsData->count() }}</p>
                </div>

                <!-- Data Tabs -->
                <div x-data="{ activeTab: 'tni' }" class="mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="-mb-px flex space-x-8">
                            <button @click="activeTab = 'tni'" :class="activeTab === 'tni' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Data TNI ({{ $tniData->count() }})
                            </button>
                            <button @click="activeTab = 'pns'" :class="activeTab === 'pns' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Data PNS ({{ $pnsData->count() }})
                            </button>
                        </nav>
                    </div>

                    <!-- TNI Data -->
                    <div x-show="activeTab === 'tni'"
                        class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                        @if($tniData->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                id_tukin</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                id_proses</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_tukin</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kdsatker</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nip</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nama_pegawai</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                jenis_pegawai</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                jenis_sk</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_sk</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                grade</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                jenis_tukin</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kotor</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                potongan</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bersih</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                pajak</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                tunj_pajak</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bersih_2</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kdbankspan</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                rekening</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nama_rekening</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nama_bank</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bulan_awal</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                tahun_awal</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bulan_akhir</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                tahun_akhir</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kali_pembayaran</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_tukin_lama</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_tukin_baru</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($tniData as $tni)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->id_tukin }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->id_proses }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nomor_tukin }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->kdsatker }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nip }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nama_pegawai }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->jenis_pegawai }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->jenis_sk }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nomor_sk }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->grade }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->jenis_tukin }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->kotor }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->potongan }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->bersih }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->pajak }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->tunj_pajak }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->bersih_2 }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->kdbankspan }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->rekening }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nama_rekening }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nama_bank }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->bulan_awal }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->tahun_awal }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->bulan_akhir }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->tahun_akhir }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->kali_pembayaran }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nomor_tukin_lama }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $tni->nomor_tukin_baru }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                    <x-dropdown align="right" width="48">
                                                        <x-slot name="trigger">
                                                            <button
                                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                                <span>Opsi</span>
                                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </x-slot>

                                                        <x-slot name="content">
                                                            <x-dropdown-link
                                                                @click="$dispatch('open-modal', 'detail-tni-{{ $tni->id }}')">
                                                                Detail
                                                            </x-dropdown-link>

                                                            @can('update', $header)
                                                                <x-dropdown-link href="{{ route('headers.edit', $tni->id) }}">
                                                                    Edit
                                                                </x-dropdown-link>
                                                            @endcan

                                                            @can('delete', $header)
                                                                <x-dropdown-link
                                                                    @click="$dispatch('open-modal', 'confirm-tni-deletion-{{ $tni->id }}')">
                                                                    Hapus
                                                                </x-dropdown-link>
                                                            @endcan
                                                        </x-slot>
                                                    </x-dropdown>

                                                    <!-- Detail Modal -->
                                                    <x-modal name="detail-tni-{{ $tni->id }}" focusable>
                                                        <div class="p-6 text-white">
                                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                                                Detail Data TNI - {{ $tni->nama_pegawai }}
                                                            </h2>

                                                            <div class="overflow-y-auto">
                                                                <table
                                                                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                                    <tbody
                                                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                ID Tukin</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->id_tukin }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                ID Proses</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->id_proses }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                Nomor Tukin</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nomor_tukin }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                Kode Satker</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->kdsatker }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                NIP</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nip }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nama Pegawai</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nama_pegawai }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Jenis Pegawai</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->jenis_pegawai }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Jenis SK</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->jenis_sk }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nomor SK</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nomor_sk }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Grade</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->grade }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Jenis Tukin</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->jenis_tukin }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Kotor</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($tni->kotor, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Potongan</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($tni->potongan, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bersih</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($tni->bersih, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Pajak</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($tni->pajak, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Tunjangan Pajak</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($tni->tunj_pajak, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bersih 2</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($tni->bersih_2, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Kode Bank</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->kdbankspan }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Rekening</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->rekening }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nama Rekening</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nama_rekening }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bank</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nama_bank }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bulan Awal</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->bulan_awal }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Tahun Awal</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->tahun_awal }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bulan Akhir</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->bulan_akhir }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Tahun Akhir</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->tahun_akhir }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Kali Pembayaran</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->kali_pembayaran }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nomor Tukin Lama</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nomor_tukin_lama }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nomor Tukin Baru</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $tni->nomor_tukin_baru }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="mt-6 flex justify-end">
                                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                                    Tutup
                                                                </x-secondary-button>
                                                            </div>
                                                        </div>
                                                    </x-modal>

                                                    <!-- Delete Confirmation Modal -->
                                                    @can('delete', $header)
                                                        <x-modal name="confirm-tni-deletion-{{ $tni->id }}" focusable>
                                                            <form method="post" action="{{ route('headers.destroy', $tni->id) }}"
                                                                class="p-6">
                                                                @csrf
                                                                @method('delete')

                                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                                    Apakah Anda yakin ingin menghapus data ini?
                                                                </h2>

                                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                                    Data yang dihapus tidak dapat dikembalikan.
                                                                </p>

                                                                <div class="mt-6 flex justify-end">
                                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                                        Batal
                                                                    </x-secondary-button>

                                                                    <x-danger-button class="ml-3">
                                                                        Hapus
                                                                    </x-danger-button>
                                                                </div>
                                                            </form>
                                                        </x-modal>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 sm:px-6">
                                {{ $tniData->links() }}
                            </div>
                        @else
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data TNI
                            </div>
                        @endif
                    </div>

                    <!-- PNS Data -->
                    <div x-show="activeTab === 'pns'"
                        class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                        @if($pnsData->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                id_tukin</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                id_proses</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_tukin</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kdsatker</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nip</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nama_pegawai</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                jenis_pegawai</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                jenis_sk</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_sk</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                grade</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                jenis_tukin</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kotor</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                potongan</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bersih</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                pajak</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                tunj_pajak</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bersih_2</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kdbankspan</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                rekening</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nama_rekening</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nama_bank</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bulan_awal</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                tahun_awal</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                bulan_akhir</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                tahun_akhir</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                kali_pembayaran</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_tukin_lama</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                nomor_tukin_baru</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($pnsData as $pns)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->id_tukin }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->id_proses }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nomor_tukin }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->kdsatker }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nip }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nama_pegawai }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->jenis_pegawai }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->jenis_sk }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nomor_sk }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->grade }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->jenis_tukin }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->kotor }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->potongan }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->bersih }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->pajak }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->tunj_pajak }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->bersih_2 }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->kdbankspan }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->rekening }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nama_rekening }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nama_bank }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->bulan_awal }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->tahun_awal }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->bulan_akhir }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->tahun_akhir }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->kali_pembayaran }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nomor_tukin_lama }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ $pns->nomor_tukin_baru }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                    <x-dropdown align="right" width="48">
                                                        <x-slot name="trigger">
                                                            <button
                                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                                <span>Opsi</span>
                                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </x-slot>

                                                        <x-slot name="content">
                                                            <x-dropdown-link
                                                                @click="$dispatch('open-modal', 'detail-pns-{{ $pns->id }}')">
                                                                Detail
                                                            </x-dropdown-link>

                                                            @can('update', $header)
                                                                <x-dropdown-link href="{{ route('headers.edit', $pns->id) }}">
                                                                    Edit
                                                                </x-dropdown-link>
                                                            @endcan

                                                            @can('delete', $header)
                                                                <x-dropdown-link
                                                                    @click="$dispatch('open-modal', 'confirm-pns-deletion-{{ $pns->id }}')">
                                                                    Hapus
                                                                </x-dropdown-link>
                                                            @endcan
                                                        </x-slot>
                                                    </x-dropdown>

                                                    <!-- Detail Modal -->
                                                    <x-modal name="detail-pns-{{ $pns->id }}" focusable>
                                                        <div class="p-6 text-white">
                                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                                                Detail Data pns - {{ $pns->nama_pegawai }}
                                                            </h2>

                                                            <div class="overflow-y-auto">
                                                                <table
                                                                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                                    <tbody
                                                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                ID Tukin</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->id_tukin }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                ID Proses</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->id_proses }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                Nomor Tukin</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nomor_tukin }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 w-1/3">
                                                                                Kode Satker</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->kdsatker }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                NIP</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nip }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nama Pegawai</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nama_pegawai }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Jenis Pegawai</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->jenis_pegawai }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Jenis SK</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->jenis_sk }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nomor SK</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nomor_sk }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Grade</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->grade }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Jenis Tukin</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->jenis_tukin }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Kotor</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($pns->kotor, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Potongan</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($pns->potongan, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bersih</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($pns->bersih, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Pajak</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($pns->pajak, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Tunjangan Pajak</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($pns->tunj_pajak, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bersih 2</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ number_format($pns->bersih_2, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Kode Bank</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->kdbankspan }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Rekening</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->rekening }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nama Rekening</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nama_rekening }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bank</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nama_bank }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bulan Awal</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->bulan_awal }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Tahun Awal</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->tahun_awal }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Bulan Akhir</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->bulan_akhir }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Tahun Akhir</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->tahun_akhir }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Kali Pembayaran</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->kali_pembayaran }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nomor Tukin Lama</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nomor_tukin_lama }}</td>
                                                                        </tr>
                                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Nomor Tukin Baru</td>
                                                                            <td
                                                                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                                {{ $pns->nomor_tukin_baru }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="mt-6 flex justify-end">
                                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                                    Tutup
                                                                </x-secondary-button>
                                                            </div>
                                                        </div>
                                                    </x-modal>

                                                    <!-- Delete Confirmation Modal -->
                                                    @can('delete', $header)
                                                        <x-modal name="confirm-pns-deletion-{{ $pns->id }}" focusable>
                                                            <form method="post" action="{{ route('headers.destroy', $pns->id) }}"
                                                                class="p-6">
                                                                @csrf
                                                                @method('delete')

                                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                                    Apakah Anda yakin ingin menghapus data ini?
                                                                </h2>

                                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                                    Data yang dihapus tidak dapat dikembalikan.
                                                                </p>

                                                                <div class="mt-6 flex justify-end">
                                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                                        Batal
                                                                    </x-secondary-button>

                                                                    <x-danger-button class="ml-3">
                                                                        Hapus
                                                                    </x-danger-button>
                                                                </div>
                                                            </form>
                                                        </x-modal>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 sm:px-6">
                                {{ $pnsData->links() }}
                            </div>
                        @else
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data PNS
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-red-500">
                    Anda tidak memiliki akses untuk melihat data ini.
                </div>
            @endcan

            <!-- Back Button -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('headers.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>