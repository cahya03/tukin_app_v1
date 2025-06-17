<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter berdasarkan parameter request
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(50);

        // Get statistics
        $stats = ActivityLogService::getActivityStats();

        // Get activity types for filter
        $activityTypes = ActivityLog::distinct('activity_type')
            ->pluck('activity_type')
            ->sort();

        return view('admin.activity-logs.index', compact('logs', 'stats', 'activityTypes'));
    }

    /**
     * Show specific activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Get user's own activity logs
     */
    public function userLogs(Request $request)
    {
        $userId = Auth::id();

        $query = ActivityLog::where('user_id', $userId)->latest();

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        return view('profile.activity-logs', compact('logs'));
    }

    /**
     * Export activity logs to CSV
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Apply same filters as index method
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->limit(10000)->get(); // Limit untuk performa

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'ID',
                'Activity Type',
                'Description',
                'User Email',
                'IP Address',
                'Status',
                'Created At'
            ]);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->activity_type,
                    $log->description,
                    $log->user_email,
                    $log->ip_address,
                    $log->status,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get dashboard statistics
     */
    public function dashboard()
    {
        $stats = ActivityLogService::getActivityStats();

        // Additional stats for dashboard
        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $failedLogins = ActivityLog::getFailedLogins(5);

        $topUsers = ActivityLog::selectRaw('user_id, user_email, COUNT(*) as activity_count')
            ->whereNotNull('user_id')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('user_id', 'user_email')
            ->orderBy('activity_count', 'desc')
            ->limit(10)
            ->get();

        $topIPs = ActivityLog::selectRaw('ip_address, COUNT(*) as request_count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('ip_address')
            ->orderBy('request_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard.logs', compact(
            'stats',
            'recentLogs',
            'failedLogins',
            'topUsers',
            'topIPs'
        ));
    }

    /**
     * Clean old logs
     */
    public function cleanup(Request $request)
    {
        $days = $request->input('days', 90); // Default 90 hari

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))
            ->delete();

        return back()->with('success', "Berhasil menghapus {$deleted} log yang lebih dari {$days} hari.");
    }
}
