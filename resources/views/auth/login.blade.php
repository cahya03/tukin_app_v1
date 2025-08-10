<x-guest-layout>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">

        <!-- Header Section -->
        <div class="text-center mb-8">
            <!-- Logo -->
            <div class="mx-auto mb-6">
                <x-application-logo class="w-16 h-16 mx-auto text-blue-600" />
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                Masuk ke SIPATUKIN
            </h2>
            <p class="text-sm text-gray-600">
                Sistem Pengarsipan Tunjangan Kinerja TNI AU
            </p>
            <div class="w-12 h-1 bg-gray-600 mx-auto mt-3 rounded-full"></div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700"
            :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <x-text-input id="email"
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        placeholder="Masukkan email Anda" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <x-text-input id="password"
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="Masukkan password Anda" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
            </div>

            <!-- Login Button -->
            <div class="pt-4">
                <x-primary-button
                    class="w-full justify-center py-3 px-4 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-white transition duration-200 transform hover:scale-[1.02]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Masuk ke Sistem') }}
                </x-primary-button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                © 2025 Disinfolahtaau - TNI Angkatan Udara
            </p>
            <div class="flex justify-center space-x-4 mt-4">
                <div class="flex items-center text-xs text-gray-400">
                    Adaptif
                </div>
                <div class="flex items-center text-xs text-gray-400">
                    Modern
                </div>
                <div class="flex items-center text-xs text-gray-400">
                    Profesional
                </div>
                <div class="flex items-center text-xs text-gray-400">
                    Unggul
                </div>
                <div class="flex items-center text-xs text-gray-400">
                    Humanis
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>