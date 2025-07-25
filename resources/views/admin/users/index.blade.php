<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Pengguna
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ openModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tombol Tambah --}}
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Daftar User
                </h3>
                <button @click="openModal = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                    + Tambah Pengguna
                </button>
            </div>

            {{-- Tabel Daftar User --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nama</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Role</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Satker</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $user->name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300 capitalize">
                                        {{ $user->role }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $user->satker->kode_satker ?? '-' }} - {{ $user->satker->nama_satker ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            {{-- Tombol Edit --}}
                                            <button @click="$dispatch('open-modal', 'edit-user-{{ $user->id }}')"
                                                class="text-yellow-600 hover:underline">Edit</button>


                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal Tambah User --}}
            <div x-show="openModal"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
                style="display: none;" x-transition>
                <div @click.away="openModal = false"
                    class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg p-6 shadow-xl"
                    x-data="{ role: 'juru_bayar' }">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Tambah Pengguna</h3>

                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                        @csrf

                        {{-- Nama --}}
                        <div>
                            <label for="name" class="block text-sm text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" id="name" name="name" required
                                class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" id="email" name="email" required
                                class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm">
                        </div>

                        {{-- Role --}}
                        <div>
                            <label for="role" class="block text-sm text-gray-700 dark:text-gray-300">Role</label>
                            <select id="role" name="role" x-model="role" required
                                class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                <option value="juru_bayar">juru_bayar</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>

                        {{-- Satker (hanya muncul jika role = juru_bayar) --}}
                        <div x-show="role === 'juru_bayar'" x-transition>
                            <label for="kode_satker" class="block text-sm text-gray-700 dark:text-gray-300">Kode
                                Satker</label>
                            <select id="kode_satker" name="kode_satker" x-bind:required="role === 'juru_bayar'"
                                class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                <option value="">Pilih Satker</option>
                                @foreach ($satkers as $kode => $label)
                                    <option value="{{ $kode }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password"
                                class="block text-sm text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm">
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" @click="openModal = false"
                                class="text-gray-600 dark:text-gray-300 hover:underline text-sm">
                                Batal
                            </button>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Edit User --}}
            @foreach($users as $user)
                <x-modal name="edit-user-{{ $user->id }}" maxWidth="2xl">
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
                        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Edit Pengguna</h2>

                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                {{-- Nama --}}
                                <div>
                                    <x-input-label for="edit-name-{{ $user->id }}" :value="__('Nama')" />
                                    <x-text-input id="edit-name-{{ $user->id }}" name="name" type="text"
                                        class="mt-1 block w-full" :value="old('name', $user->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                {{-- Email --}}
                                <div>
                                    <x-input-label for="edit-email-{{ $user->id }}" :value="__('Email')" />
                                    <x-text-input id="edit-email-{{ $user->id }}" name="email" type="email"
                                        class="mt-1 block w-full" :value="old('email', $user->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                {{-- Role --}}
                                <div>
                                    <x-input-label for="edit-role-{{ $user->id }}" :value="__('Role')" />
                                    <select id="edit-role-{{ $user->id }}" name="role"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm">
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            admin</option>
                                        <option value="juru_bayar" {{ old('role', $user->role) == 'juru_bayar' ? 'selected' : '' }}>juru_bayar</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                </div>

                                {{-- Satker (jika juru_bayar) --}}
                                <div>
                                    <x-input-label for="edit-kode_satker-{{ $user->id }}" :value="__('Satker')" />
                                    <select id="edit-kode_satker-{{ $user->id }}" name="kode_satker"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm"
                                        {{ $user->role === 'juru_bayar' ? '' : 'disabled' }}>
                                        <option value="">-- Pilih Satker --</option>
                                        @foreach($satkers as $kode => $label)
                                            <option value="{{ $kode }}" {{ old('kode_satker', $user->kode_satker) == $kode ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if($user->role !== 'juru_bayar')
                                        <input type="hidden" name="kode_satker" value="{{ $user->kode_satker }}">
                                    @endif

                                    <x-input-error :messages="$errors->get('kode_satker')" class="mt-2" />
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <div class="flex justify-end space-x-3 mt-6">
                                <x-secondary-button type="button"
                                    @click="$dispatch('close-modal', 'edit-user-{{ $user->id }}')">
                                    Batal
                                </x-secondary-button>
                                <x-primary-button type="submit">
                                    Simpan Perubahan
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            @endforeach


        </div>
    </div>
</x-app-layout>