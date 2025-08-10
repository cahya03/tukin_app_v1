<x-guest-layout>    
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
        <!-- Main Card -->
        <div class="bg-white rounded-2xl card-shadow p-8 sm:p-12 text-center">
            
            <!-- Logo Section -->
            <div class="mb-8">
                <div class="w-32 h-32 mx-auto mb-4 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/logo-tni-au.svg') }}" alt="Logo" class="mx-auto">
                </div>
            </div>

            <!-- Title Section -->
            <div class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
                    SIPATUKIN TNI AU
                </h1>
                <div class="w-16 h-1 bg-gray-600 mx-auto mb-4 rounded-full"></div>
                <p class="text-lg text-gray-600 font-medium">
                    Sistem Pengarsipan<br>
                    Tunjangan Kinerja TNI AU
                </p>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <p class="text-gray-500 leading-relaxed">
                    Selamat datang di sistem informasi pengarsipan tunjangan kinerja TNI Angkatan Udara. 
                    Silakan login untuk melanjutkan ke sistem.
                </p>
            </div>

            <!-- Login Button -->
            <div class="mb-6">
                <a href="{{ route('login') }}" >
                    <x-primary-button class="w-full justify-center py-3 px-4 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-white transition duration-200 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Masuk ke Sistem') }}
                    </x-primary-button>
                </a>
            </div>

            <!-- Footer -->
            <div class="pt-6 border-t border-gray-200">
                <p class="text-gray-400 text-sm">
                    © 2025 Disinfolahtaau
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>