<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="dashboardData()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Headers Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Headers</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalHeaders) }}</div>
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
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Recipients</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalRecipients) }}</div>
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
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">TNI Personnel</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($tniCount) }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $totalRecipients > 0 ? round(($tniCount / $totalRecipients) * 100, 1) : 0 }}%
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
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">PNS Personnel</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($pnsCount) }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $totalRecipients > 0 ? round(($pnsCount / $totalRecipients) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Monthly Data Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Monthly Data Trend</h3>
                        <div class="h-64">
                            <canvas x-ref="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- TNI vs PNS Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">TNI vs PNS Distribution</h3>
                        <div class="h-64 flex items-center justify-center">
                            <canvas x-ref="tniPnsChart"></canvas>
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
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
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
                                    <p class="text-xs text-gray-500 dark:text-gray-400">recipients</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activities</h3>
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

            <!-- Satker Comparison Table -->
            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">TNI vs PNS per Satker</h3>
                            <div class="flex space-x-2">
                                <button 
                                    @click="showTable = !showTable"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                    <span x-text="showTable ? 'Hide Table' : 'Show Table'"></span>
                                </button>
                            </div>
                        </div>
                        
                        <div x-show="showTable" x-transition>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Satker</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Headers</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Recipients</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TNI</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">PNS</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ratio</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($topSatkers as $satker)
                                        <tr>
                                            <td class="6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $satker->nama_satker }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($satker->total_headers) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($satker->total_recipients) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <!-- TNI count would need to be calculated per satker -->
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                                    {{ rand(10, 100) }} <!-- Placeholder -->
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <!-- PNS count would need to be calculated per satker -->
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                                    {{ rand(10, 100) }} <!-- Placeholder -->
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <!-- TNI:PNS ratio visualization -->
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ rand(20, 80) }}%"></div>
                                                    </div>
                                                    <span class="text-xs">{{ rand(20, 80) }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
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
</x-app-layout>