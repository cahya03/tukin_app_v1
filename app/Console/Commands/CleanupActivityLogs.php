<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class CleanupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'logs:cleanup 
                            {--days=90 : Hari untuk menjaga log (default: 90)}
                            {--dry-run : Simulasi tanpa menghapus data}';

    /**
     * The console command description.
     */
    protected $description = 'Membersihkan activity logs yang sudah lama';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');

        $cutoffDate = now()->subDays($days);

        $query = ActivityLog::where('created_at', '<', $cutoffDate);
        $count = $query->count();

        if ($count === 0) {
            $this->info("Tidak ada log yang perlu dibersihkan (lebih dari {$days} hari).");
            return;
        }

        $this->info("Ditemukan {$count} log yang akan dihapus (lebih dari {$days} hari).");

        if ($dryRun) {
            $this->warn('Mode DRY RUN - Tidak ada data yang benar-benar dihapus.');
            return;
        }

        if ($this->confirm('Apakah Anda yakin ingin menghapus log tersebut?')) {
            $deleted = $query->delete();
            $this->info("Berhasil menghapus {$deleted} activity logs.");
        } else {
            $this->info('Pembersihan dibatalkan.');
        }
    }
}
