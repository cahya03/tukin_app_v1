<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Tukin
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifications -->
            <x-notification :success="$sukses ?? null" :errors="$errors->all() ?? null" />

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Daftar Header Tukin
                </h3>
                
                @can('create', App\Models\Header::class)
                    <button @click="$dispatch('open-modal', 'create-header')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Header
                    </button>
                @endcan
            </div>
            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('headers.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satker</label>
                            <select name="satker" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <option value="">Semua Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->kode_satker }}" {{ request('satker') == $satker->kode_satker ? 'selected' : '' }}>
                                        {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dibuat Oleh</label>
                            <select name="creator" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <option value="">Semua User</option>
                                @foreach($creators as $creator)
                                    <option value="{{ $creator->id }}" {{ request('creator') == $creator->id ? 'selected' : '' }}>
                                        {{ $creator->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Dari</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Sampai</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari berdasarkan nama header..."
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        </div>
                        
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Filter
                            </button>
                            <a href="{{ route('headers.index') }}" class="bg-gray-500 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Reset
                            </a>
                        </div>
                        
                        <div class="flex items-end">
                            <a href="{{ route('headers.export', request()->all()) }}" 
                               class="bg-green-500 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Header Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if($headers->isEmpty())
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Belum ada header yang dibuat.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Header</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Satker</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dibuat Oleh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($headers as $header)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $header->nama_header }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $header->satker->nama_satker ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($header->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $header->creator->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('headers.show', $header->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            Lihat
                                        </a>
                                        
                                        @can('update', $header)
                                        <button @click="$dispatch('open-modal', 'edit-header-{{ $header->id }}')"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            Edit
                                        </button>
                                        @endcan
                                        
                                        @can('delete', $header)
                                        <form action="{{ route('headers.destroy', $header->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus header ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        {{ $headers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal-create-header :satkers="$satkers" />

    <!-- Edit Modals -->
    @foreach($headers as $header)
        @can('update', $header)
        <x-modal name="edit-header-{{ $header->id }}" maxWidth="2xl">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Edit Header</h2>
                
                <form method="POST" action="{{ route('headers.update', $header->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Nama Header -->
                        <div>
                            <x-input-label for="edit-nama_header-{{ $header->id }}" :value="__('Nama Header')" />
                            <x-text-input id="edit-nama_header-{{ $header->id }}" name="nama_header" type="text" 
                                class="mt-1 block w-full" :value="old('nama_header', $header->nama_header)" required />
                            <x-input-error :messages="$errors->get('nama_header')" class="mt-2" />
                        </div>
                        
                        <!-- Satker -->
                        <div>
                            <x-input-label for="edit-kode_satker-{{ $header->id }}" :value="__('Satker')" />
                            <select id="edit-kode_satker-{{ $header->id }}" name="kode_satker" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300"
                                {{ Auth::user()->role === 'juru_bayar' ? 'disabled' : '' }}>
                                
                                @if(Auth::user()->role === 'juru_bayar')
                                    <option value="{{ $header->kode_satker }}" selected>
                                        {{ $header->satker->nama_satker }}
                                    </option>
                                @else
                                    <option value="">-- Pilih Satker --</option>
                                    @foreach($satkers as $satker)
                                        <option value="{{ $satker->kode_satker }}" 
                                            {{ old('kode_satker', $header->kode_satker) == $satker->kode_satker ? 'selected' : '' }}>
                                            {{ $satker->nama_satker }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            
                            @if(Auth::user()->role === 'juru_bayar')
                                <input type="hidden" name="kode_satker" value="{{ $header->kode_satker }}">
                            @endif
                            
                            <x-input-error :messages="$errors->get('kode_satker')" class="mt-2" />
                        </div>
                        
                        <!-- Tanggal -->
                        <div>
                            <x-input-label for="edit-tanggal-{{ $header->id }}" :value="__('Tanggal')" />
                            <x-text-input
                                id="edit-tanggal-{{ $header->id }}"
                                name="tanggal"
                                type="date"
                                class="mt-1 block w-full"
                                :value="old('tanggal', \Carbon\Carbon::parse($header->tanggal)->format('Y-m-d'))"
                                required
                            />
                            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-header-{{ $header->id }}')">
                            Batal
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
        @endcan
    @endforeach
</x-app-layout>