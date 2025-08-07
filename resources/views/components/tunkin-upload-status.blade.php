<div>
    <div class="mb-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
            Rekap Upload Tukin ({{ $year }})
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
            <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                <label for="year" class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">
                    Pilih Tahun:
                </label>
                <select id="year" name="year" onchange="this.form.submit()" class="...">
                    @foreach (range(now()->year, 2020) as $yearOption)
                        <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>
                            {{ $yearOption }}
                        </option>
                    @endforeach
                </select>
            </form>
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
                    @foreach ($uploadStatus as $item)
                        <tr>
                            <td class="px-4 py-2 text-left font-medium text-gray-900 dark:text-gray-100">
                                {{ $item['nama_satker'] }}
                            </td>
                            @foreach ($item['bulan_status'] as $status)
                                <td class="px-2 py-1">
                                    @if ($status)
                                        <span class="inline-block w-4 h-4 bg-green-500 rounded-full" title="Sudah upload"></span>
                                    @else
                                        <span class="inline-block w-4 h-4 bg-red-500 rounded-full" title="Belum upload"></span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>