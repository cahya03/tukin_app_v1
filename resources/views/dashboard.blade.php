<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if(Auth::user()->role === 'admin')
                {{ __('Admin Dashboard') }}
            @elseif (Auth::user()->role === 'juru_bayar')
                {{ __('Dashboard Satker ') . Auth::user()->satker->nama_satker }}
            @else
                {{ __('Dashboard') }}
            @endif
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifications -->
            <x-notification :success="$sukses ?? null" :errors="$errors->all() ?? null" />
            @if ((Auth::user()->role === 'admin'))
                <div class="py-12" x-data="dashboardData()">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <!-- Cek Card-->
                        <div class="mb-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                Rekap Upload Tukin ({{ now()->year }})
                            </h2>

                            <div class="overflow-x-auto">
                                <div class="mb-4 flex items-center space-x-4 text-sm text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                                        <span>Sudah diupload</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                                        <span>Belum diupload</span>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-center">
                                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Satker</th>
                                            @foreach (range(1, 12) as $month)
                                                <th class="px-2 py-1">
                                                    {{ strtoupper(\Carbon\Carbon::create()->month($month)->translatedFormat('M')) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                                        @foreach ($tunkinUploadStatus as $item)
                                            <tr>
                                                <td class="px-4 py-2 text-left font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item['nama_satker'] }}
                                                </td>
                                                @foreach ($item['bulan_status'] as $status)
                                                    <td class="px-2 py-1">
                                                        @if ($status)
                                                            <span class="inline-block w-4 h-4 bg-green-500 rounded-full"
                                                                title="Sudah upload"></span>
                                                        @else
                                                            <span class="inline-block w-4 h-4 bg-red-500 rounded-full"
                                                                title="Belum upload"></span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Arsip Card-->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            @foreach ($headers as $header)
                                <x-arsip-card :header="$header" />
                            @endforeach
                        </div>
                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <!-- Total Headers Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Headers
                                            </div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ number_format($stats['totalHeaders']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Recipients Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                                Recipients</div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ number_format($stats['totalRecipients']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TNI Count Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">TNI Personnel
                                            </div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ number_format($stats['tniCount']) }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $stats['totalRecipients'] > 0 ? round(($stats['tniCount'] / $stats['totalRecipients']) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PNS Count Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">PNS Personnel
                                            </div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ number_format($stats['pnsCount']) }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $stats['totalRecipients'] > 0 ? round(($stats['pnsCount'] / $stats['totalRecipients']) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Satkers and Recent Activities -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Top Satkers -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top 10 Satkers</h3>
                                    <div class="space-y-4">
                                        @foreach($topSatkers as $index => $satker)
                                            <div
                                                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ Str::limit($satker->nama_satker, 40) }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $satker->total_headers }} headers
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($satker->total_recipients) }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">personel</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activities -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activities
                                    </h3>
                                    <div class="space-y-4">
                                        @forelse($recentActivities as $activity)
                                            <div class="flex items-start space-x-3">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-900 dark:text-white">
                                                        {{ $activity->description ?? 'Activity logged' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <p class="text-gray-500 dark:text-gray-400">No recent activities</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function dashboardData() {
                        return {
                            showTable: false,
                            monthlyChart: null,
                            tniPnsChart: null,

                            init() {
                                this.$nextTick(() => {
                                    this.initMonthlyChart();
                                    this.initTniPnsChart();
                                });
                            },

                            initMonthlyChart() {
                                const ctx = this.$refs.monthlyChart.getContext('2d');

                                // Monthly data from Laravel
                                const monthlyData = @json($monthlyData);

                                const labels = monthlyData.map(item => {
                                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                    return months[item.month - 1] + ' ' + item.year;
                                });

                                const headersData = monthlyData.map(item => item.total_headers);
                                const recipientsData = monthlyData.map(item => item.total_recipients);

                                this.monthlyChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Headers',
                                            data: headersData,
                                            borderColor: 'rgb(59, 130, 246)',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            tension: 0.4
                                        }, {
                                            label: 'Recipients',
                                            data: recipientsData,
                                            borderColor: 'rgb(16, 185, 129)',
                                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                            tension: 0.4
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: true,
                                                position: 'top',
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.1)'
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.1)'
                                                }
                                            }
                                        }
                                    }
                                });
                            },

                            initTniPnsChart() {
                                const ctx = this.$refs.tniPnsChart.getContext('2d');

                                // TNI vs PNS data from Laravel
                                const tniPnsData = @json($tniPnsData);

                                const labels = tniPnsData.map(item => item.tni_pns);
                                const data = tniPnsData.map(item => item.total);
                                const colors = ['#EF4444', '#8B5CF6']; // Red for TNI, Purple for PNS

                                this.tniPnsChart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            data: data,
                                            backgroundColor: colors,
                                            borderColor: colors,
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: true,
                                                position: 'bottom',
                                            }
                                        }
                                    }
                                });
                            }
                        }
                    }
                </script>
            @elseif ((Auth::user()->role === 'juru_bayar'))
                <!-- JURU BAYAR DASHBOARD CONTENT -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Card Total Penerima -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Penerima</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $stats['totalPenerima'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Total TNI -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 dark:bg-red-900 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Personil TNI</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['tniCount'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $stats['totalPenerima'] > 0 ? round(($stats['tniCount'] / $stats['totalPenerima']) * 100, 1) : 0 }}%
                                        dari
                                        total
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Total PNS -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Personil PNS</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pnsCount'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $stats['totalPenerima'] > 0 ? round(($stats['pnsCount'] / $stats['totalPenerima']) * 100, 1) : 0 }}%
                                        dari
                                        total
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Data Tukin -->
                <!-- Di bagian Recent Data Tukin, perbaikan tabel -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Tukin Terbaru</h3>
                            <a href="{{ route('headers.index') }}"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Periode</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jumlah Penerima</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total (Rp)</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentHeaders as $header)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ \Carbon\Carbon::parse($header->tanggal)->format('F Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $header->nama_header }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $header->tukins->count() }} orang
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    TNI: {{ $header->tukins->where('tni_pns', 'TNI')->count() }},
                                                    PNS: {{ $header->tukins->where('tni_pns', 'PNS')->count() }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                Rp {{ number_format($header->tukins->sum('bersih'), 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('headers.show', $header->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('headers.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg shadow flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Header Baru
                    </a>

                    <a href="*"
                        class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg shadow flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export Data
                    </a>
                </div>
                <script>
                    function juruBayarCharts() {
                        return {
                            initCharts() {
                                this.initPangkatChart();
                                this.initPaymentChart();
                            },

                            initPangkatChart() {
                                const ctx = this.$refs.pangkatChart.getContext('2d');
                                const pangkatData = @json($pangkatData);

                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: pangkatData.map(item => item.pangkat),
                                        datasets: [{
                                            label: 'Jumlah Personil',
                                            data: pangkatData.map(item => item.total),
                                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                            borderColor: 'rgba(59, 130, 246, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    precision: 0
                                                }
                                            }
                                        }
                                    }
                                });
                            },

                            initPaymentChart() {
                                const ctx = this.$refs.paymentChart.getContext('2d');
                                const paymentData = @json($paymentHistory);

                                new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: paymentData.map(item => {
                                            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                                                'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                                            return monthNames[item.month - 1] + ' ' + item.year;
                                        }),
                                        datasets: [{
                                            label: 'Total Pembayaran (Rp)',
                                            data: paymentData.map(item => item.total),
                                            borderColor: 'rgba(16, 185, 129, 1)',
                                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                            fill: true,
                                            tension: 0.3
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function (value) {
                                                        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                                    }
                                                }
                                            }
                                        },
                                        plugins: {
                                            tooltip: {
                                                callbacks: {
                                                    label: function (context) {
                                                        return 'Total: Rp ' + context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        }
                    }
                </script>
            @else
            @endif
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</x-app-layout>