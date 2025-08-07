<x-modal name="print-report" maxWidth="lg">
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cetak Laporan</h2>

        <form method="GET" action="{{ route('laporan.pdf') }}" target="_blank">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Bulan Awal -->
                <div>
                    <x-input-label for="bulan_awal" :value="__('Bulan Awal')" />
                    <select id="bulan_awal" name="bulan_awal" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        @foreach(range(1, 12) as $bulan)
                            <option value="{{ $bulan }}">{{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulan Akhir -->
                <div>
                    <x-input-label for="bulan_akhir" :value="__('Bulan Akhir')" />
                    <select id="bulan_akhir" name="bulan_akhir" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        @foreach(range(1, 12) as $bulan)
                            <option value="{{ $bulan }}">{{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Awal -->
                <div>
                    <x-input-label for="tahun_awal" :value="__('Tahun Awal')" />
                    <select id="tahun_awal" name="tahun_awal" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        @foreach(range(now()->year - 5, now()->year + 1) as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Akhir -->
                <div>
                    <x-input-label for="tahun_akhir" :value="__('Tahun Akhir')" />
                    <select id="tahun_akhir" name="tahun_akhir" class="block w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                        @foreach(range(now()->year - 5, now()->year + 1) as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'print-report')">Batal</x-secondary-button>
                <x-primary-button type="submit">Cetak</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
