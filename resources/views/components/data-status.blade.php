<!-- resources/views/components/data-status.blade.php -->
@props(['type', 'data', 'search'])

@if($search)
    <div class="px-4 py-2 bg-blue-50 dark:bg-blue-900 text-blue-800 dark:text-blue-100 text-sm">
        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong>
        @if($data->count() > 0)
            ({{ $data->total() }} hasil ditemukan)
        @endif
    </div>
@endif

@if($data->isEmpty())
    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
        @if($search)
            Tidak ditemukan data {{ $type }} yang sesuai dengan pencarian "{{ $search }}"
        @else
            Tidak ada data {{ $type }}
        @endif
    </div>
@endif