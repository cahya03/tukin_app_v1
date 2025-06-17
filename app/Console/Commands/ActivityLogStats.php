<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Console\Command;

class ActivityLogStats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'logs:stats 
                            {--days=7 : Jumlah hari untuk statistik}
                            {--detailed : Tampilkan statistik detail}';

    /**
     * The console command description.
     */
    protected $description = 'Menampilkan statistik activity logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $detailed = $this->option('detailed');

        $stats = ActivityLogService::getActivityStats($days);

        $this->info("=== STATISTIK ACTIVITY LOG ({$days} HARI TERAKHIR) ===");
        $this->newLine();

        $this->table(
            ['Metrik', 'Jumlah'],
            [
                ['Total Aktivitas', number_format($stats['total_activities'])],
                ['Login Berhasil', number_format($stats['login_success'])],
                ['Login Gagal', number_format($stats['login_failed'])],
                ['Akses Tidak Terotorisasi', number_format($stats['unauthorized_access'])],
                ['Unique Users', number_format($stats['unique_users'])],
                ['Unique IP Addresses', number_format($stats['unique_ips'])],
            ]
        );

        if ($detailed) {
            $this->showDetailedStats($days);
        }
    }

    private function showDetailedStats($days)
    {
        $this->newLine();
        $this->info("=== STATISTIK DETAIL ===");

        // Top Activities
        $topActivities = ActivityLog::selectRaw('activity_type, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('activity_type')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $this->newLine();
        $this->info("Top 10 Jenis Aktivitas:");
        $this->table(
            ['Activity Type', 'Jumlah'],
            $topActivities->map(fn($item) => [$item->activity_type, number_format($item->count)])
        );

        // Top IPs
        $topIPs = ActivityLog::selectRaw('ip_address, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $this->newLine();
        $this->info("Top 10 IP Addresses:");
        $this->table(
            ['IP Address', 'Jumlah Request'],
            $topIPs->map(fn($item) => [$item->ip_address, number_format($item->count)])
        );

        // Daily Activity
        $dailyStats = ActivityLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $this->newLine();
        $this->info("Aktivitas Harian:");
        $this->table(
            ['Tanggal', 'Jumlah Aktivitas'],
            $dailyStats->map(fn($item) => [$item->date, number_format($item->count)])
        );
    }
}
