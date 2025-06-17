<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'logs:export 
                            {--format=csv : Format export (csv, json)}
                            {--days=30 : Hari terakhir untuk export}
                            {--activity= : Filter berdasarkan activity type}
                            {--output= : Path output file}';

    /**
     * The console command description.
     */
    protected $description = 'Export activity logs ke file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $format = $this->option('format');
        $days = $this->option('days');
        $activityType = $this->option('activity');
        $outputPath = $this->option('output');

        $query = ActivityLog::with('user')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc');

        if ($activityType) {
            $query->where('activity_type', $activityType);
        }

        $logs = $query->get();

        if ($logs->isEmpty()) {
            $this->warn('Tidak ada data log untuk diekspor.');
            return;
        }

        $filename = $outputPath ?: storage_path('app/exports/activity_logs_' . now()->format('Y-m-d_H-i-s') . '.' . $format);

        // Ensure directory exists
        $directory = dirname($filename);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        switch ($format) {
            case 'csv':
                $this->exportToCsv($logs, $filename);
                break;
            case 'json':
                $this->exportToJson($logs, $filename);
                break;
            default:
                $this->error("Format tidak didukung: {$format}");
                return;
        }

        $this->info("Export berhasil: {$filename}");
        $this->info("Total records: " . number_format($logs->count()));
    }

    private function exportToCsv($logs, $filename)
    {
        $file = fopen($filename, 'w');

        // Header
        fputcsv($file, [
            'ID',
            'Activity Type',
            'Description',
            'User Email',
            'IP Address',
            'User Agent',
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
                $log->user_agent,
                $log->status,
                $log->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($file);
    }

    private function exportToJson($logs, $filename)
    {
        $data = $logs->map(function ($log) {
            return [
                'id' => $log->id,
                'activity_type' => $log->activity_type,
                'description' => $log->description,
                'user_email' => $log->user_email,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'request_url' => $log->request_url,
                'http_method' => $log->http_method,
                'status' => $log->status,
                'request_data' => $log->request_data,
                'response_data' => $log->response_data,
                'created_at' => $log->created_at->toISOString(),
            ];
        });

        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
    }
}
