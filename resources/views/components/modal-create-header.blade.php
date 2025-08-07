@props(['satkers' => []])

<x-modal name="create-header" maxWidth="2xl">
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Tambah Header Tukin</h2>
        
        <form method="POST" action="{{ route('headers.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Nama Header -->
                <div>
                    <x-input-label for="nama_header" :value="__('Nama Header')" />
                    <x-text-input id="nama_header" name="nama_header" type="text" class="mt-1 block w-full" 
                        :value="old('nama_header')" required autofocus />
                    <x-input-error :messages="$errors->get('nama_header')" class="mt-2" />
                </div>
                
                <!-- Deskripsi -->
                <div>
                    <x-input-label for="deskripsi_header" :value="__('Deskripsi')" />
                    <x-text-input id="deskripsi_header" name="deskripsi_header" type="text" class="mt-1 block w-full" 
                        :value="old('deskripsi_header')" required />
                    <x-input-error :messages="$errors->get('deskripsi_header')" class="mt-2" />
                </div>
                
                <!-- Satker -->
                <div>
                    <x-input-label for="kode_satker" :value="__('Satker')" />
                    <select id="kode_satker" name="kode_satker" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300"
                        {{ Auth::user()->role === 'juru_bayar' ? 'disabled' : '' }}>
                        
                        @if(Auth::user()->role === 'juru_bayar')
                            <option value="{{ Auth::user()->kode_satker }}" selected>
                                {{ Auth::user()->satker->nama_satker }}
                            </option>
                        @else
                            <option value="">-- Pilih Satker --</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->kode_satker }}" {{ old('kode_satker') == $satker->kode_satker ? 'selected' : '' }}>
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    
                    @if(Auth::user()->role === 'juru_bayar')
                        <input type="hidden" name="kode_satker" value="{{ Auth::user()->kode_satker }}">
                    @endif
                    
                    <x-input-error :messages="$errors->get('kode_satker')" class="mt-2" />
                </div>
                
                <!-- Tanggal -->
                <div>
                    <x-input-label for="tanggal" :value="__('Tanggal')" />
                    <x-text-input 
                        id="tanggal" 
                        name="tanggal" 
                        type="date" 
                        class="mt-1 block w-full" 
                        :value="old('tanggal', \Carbon\Carbon::now()->format('Y-m-d'))"
                        required 
                    />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <!-- File TNI -->
                <div>
                    <x-input-label for="file_tni" :value="__('File Excel TNI')" />
                    <x-text-input id="file_tni" name="file_tni" type="file" class="mt-1 block w-full" 
                        accept=".xlsx,.xls" required />
                    <x-input-error :messages="$errors->get('file_tni')" class="mt-2" />
                </div>
                
                <!-- File PNS -->
                <div>
                    <x-input-label for="file_pns" :value="__('File Excel PNS')" />
                    <x-text-input id="file_pns" name="file_pns" type="file" class="mt-1 block w-full" 
                        accept=".xlsx,.xls" required />
                    <x-input-error :messages="$errors->get('file_pns')" class="mt-2" />
                </div>
            </div>
                <!-- File PDF -->
                <div>
                    <x-input-label for="file_pns" :value="__('File PDF')" />
                    <x-text-input id="file_pns" name="file_pdf" type="file" class="mt-1 block w-full" 
                        accept=".pdf" required />
                    <x-input-error :messages="$errors->get('file_pdf')" class="mt-2" />
                </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-header')">
                    Batal
                </x-secondary-button>
                <x-primary-button type="submit">
                    Simpan
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>