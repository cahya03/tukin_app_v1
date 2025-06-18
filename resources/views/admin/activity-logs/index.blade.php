<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Activity Logs
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Activity Type</label>
                            <select name="activity_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <option value="">All Types</option>
                                @foreach($activityTypes as $type)
                                    <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <option value="">All Status</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by description, email, or IP address..."
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        </div>
                        
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Filter
                            </button>
                            <a href="{{ route('admin.activity-logs.index') }}" class="bg-gray-500 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Reset
                            </a>
                        </div>
                        
                        <div class="flex items-end">
                            <a href="{{ route('admin.activity-logs.export', request()->all()) }}" 
                               class="bg-green-500 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Section -->
            @if(isset($stats))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-4 rounded border dark:border-blue-800">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_logs'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total Logs</div>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-4 rounded border dark:border-green-800">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['today_logs'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Today's Logs</div>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-4 rounded border dark:border-yellow-800">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['failed_logins'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Failed Logins</div>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 p-4 rounded border dark:border-red-800">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['unique_users'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Active Users</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Logs Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if($logs->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No activity logs found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Activity Type
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            IP Address
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Created At
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($logs as $log)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $log->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 border dark:border-blue-700">
                                                    {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $log->user ? $log->user->name : 'System' }}
                                                @if($log->user_email)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user_email }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                                {{ $log->description }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $log->ip_address }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                @if($log->status == 'success')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 border dark:border-green-700">
                                                        Success
                                                    </span>
                                                @elseif($log->status == 'failed')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 border dark:border-red-700">
                                                        Failed
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border dark:border-gray-600">
                                                        {{ ucfirst($log->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-200">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $logs->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cleanup Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-red-600 dark:text-red-400">Cleanup Old Logs</h3>
                    <form method="POST" action="{{ route('admin.activity-logs.cleanup') }}" 
                          onsubmit="return confirm('Are you sure you want to delete old logs? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <div class="flex items-end gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delete logs older than (days)</label>
                                <input type="number" name="days" value="90" min="1" max="365" 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            </div>
                            <button type="submit" class="bg-red-500 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Cleanup Logs
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>