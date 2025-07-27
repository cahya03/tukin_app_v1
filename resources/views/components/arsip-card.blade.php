@props(['header'])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-4">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
        {{ $header->nama_header }}
    </h3>
    <p class="text-sm text-gray-600 dark:text-gray-300">
        Kode Satker: {{ $header->kode_satker }}
    </p>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Tanggal: {{ \Carbon\Carbon::parse($header->tanggal)->translatedFormat('d M Y') }}
    </p>

    <div class="mt-4 flex flex-col space-y-2">
        @if($header->file_tni_path)
            <a href="{{ asset($header->file_tni_path) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl text-sm text-center" download>
                📥 Download TNI
            </a>
        @endif
        @if($header->file_pns_path)
            <a href="{{ asset($header->file_pns_path) }}" class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-xl text-sm text-center" download>
                📥 Download PNS
            </a>
        @endif
    </div>
</div>
