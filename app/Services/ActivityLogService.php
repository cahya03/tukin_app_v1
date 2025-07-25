<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ActivityLogService
{
    // Konstanta untuk tipe aktivitas
    const LOGIN_SUCCESS = 'login_success';
    const LOGIN_FAILED = 'login_failed';
    const LOGOUT = 'logout';
    const REGISTER = 'register';
    const PASSWORD_RESET = 'password_reset';
    const CREATE_HEADER = 'create_header';
    const VIEW_HEADER = 'view_header';
    const UPDATE_HEADER = 'update_header';
    const DELETE_HEADER = 'delete_header';
    const CREATE_POST = 'create_post';
    const VIEW_POST = 'view_post';
    const UPDATE_POST = 'update_post';
    const DELETE_POST = 'delete_post';
    const CREATE_USER = 'create_user';
    const VIEW_USER = 'view_user';
    const UPDATE_USER = 'update_user';
    const DELETE_USER = 'delete_user';
    const PROFILE_UPDATE = 'profile_update';
    const EMAIL_VERIFICATION = 'email_verification';
    const UNAUTHORIZED_ACCESS = 'unauthorized_access';


    /**
     * Log aktivitas user
     */
    public static function log(
        string $activityType,
        string $description,
        ?Request $request = null,
        ?array $additionalData = null,
        string $status = 'success',
        ?string $errorMessage = null
    ): void {
        try {
            $request = $request ?: request();
            $user = Auth::user();

            $logData = [
                'activity_type' => $activityType,
                'description' => $description,
                'user_id' => $user?->id,
                'user_email' => $user?->email ?? $request->input('email'),
                'ip_address' => self::getClientIp($request),
                'user_agent' => $request->userAgent(),
                'request_url' => $request->fullUrl(),
                'http_method' => $request->method(),
                'status' => $status,
                'session_id' => $request->session() ? $request->session()->getId() : null,
                'error_message' => $errorMessage,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Filter request data yang sensitif
            $requestData = $request->except([
                'password',
                'password_confirmation',
                'current_password',
                '_token',
                '_method'
            ]);

            if (!empty($requestData)) {
                $logData['request_data'] = json_encode($requestData);
            }

            if ($additionalData) {
                $logData['response_data'] = json_encode($additionalData);
            }

            // Debug: Log data yang akan disimpan
            Log::info('Attempting to save activity log', $logData);

            $result = ActivityLog::create($logData);

            // Debug: Confirm save success
            if ($result) {
                Log::info('Activity log saved successfully', ['id' => $result->id]);
            }
        } catch (\Exception $e) {
            // Log error ke Laravel log file jika gagal menyimpan ke database
            Log::error('Failed to save activity log: ' . $e->getMessage(), [
                'activity_type' => $activityType,
                'description' => $description,
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Get real client IP address
     */
    private static function getClientIp(Request $request): string
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP'];

        foreach ($ipKeys as $key) {
            if ($request->server($key)) {
                $ips = explode(',', $request->server($key));
                return trim($ips[0]);
            }
        }

        return $request->ip() ?? '127.0.0.1';
    }

    /**
     * Log aktivitas login berhasil
     */
    public static function logLoginSuccess(?Request $request = null): void
    {
        self::log(
            self::LOGIN_SUCCESS,
            'User berhasil login',
            $request
        );
    }

    /**
     * Log aktivitas login gagal
     */
    public static function logLoginFailed(string $email, ?Request $request = null): void
    {
        $request = $request ?: request();
        $request->merge(['email' => $email]);

        self::log(
            self::LOGIN_FAILED,
            'Percobaan login gagal untuk email: ' . $email,
            $request,
            null,
            'failed'
        );
    }

    /**
     * Log aktivitas logout
     */
    public static function logLogout(?Request $request = null): void
    {
        self::log(
            self::LOGOUT,
            'User logout dari sistem',
            $request
        );
    }

    /**
     * Log aktivitas registrasi
     */
    public static function logRegister(?Request $request = null): void
    {
        self::log(
            self::REGISTER,
            'User baru mendaftar',
            $request
        );
    }

    /**
     * Log aktivitas membuat header
     */
    public static function logCreateHeader(array $headerData, ?Request $request = null): void
    {
        self::log(
            self::CREATE_HEADER,
            'Membuat header baru: ' . ($headerData['title'] ?? 'Unknown'),
            $request,
            ['header_id' => $headerData['id'] ?? null]
        );
    }

    /**
     * Log aktivitas melihat header
     */
    public static function logViewHeader(int $headerId, string $headerTitle, ?Request $request = null): void
    {
        self::log(
            self::VIEW_HEADER,
            'Melihat header: ' . $headerTitle,
            $request,
            ['header_id' => $headerId]
        );
    }

    /**
     * Log aktivitas mengupdate header
     */
    public static function logUpdateHeader(int $headerId, array $changes, ?Request $request = null): void
    {
        self::log(
            self::UPDATE_HEADER,
            'Mengupdate header ID: ' . $headerId,
            $request,
            ['header_id' => $headerId, 'changes' => $changes]
        );
    }

    /**
     * Log aktivitas menghapus header
     */
    public static function logDeleteHeader(int $headerId, string $headerTitle, ?Request $request = null): void
    {
        self::log(
            self::DELETE_HEADER,
            'Menghapus header: ' . $headerTitle,
            $request,
            ['header_id' => $headerId]
        );
    }
    /**
     * Log aktivitas melihat user
     */
    public static function logViewUser(int $userId, string $userTitle, ?Request $request = null): void
    {
        self::log(
            self::VIEW_USER,
            'Melihat user: ' . $userTitle,
            $request,
            ['user_id' => $userId]
        );
    }
    /**
     * Log aktivitas membuat user
     */
    public static function logCreateUser(array $userData, ?Request $request = null): void
    {
        self::log(
            self::CREATE_USER,
            'Membuat user baru: ' . ($userData['title'] ?? 'Unknown'),
            $request,
            ['user_id' => $userData['id'] ?? null]
        );
    }
    
    /**
     * Log aktivitas mengupdate user
     */
    public static function logUpdateUser(int $userId, array $changes, ?Request $request = null): void
    {
        self::log(
            self::UPDATE_USER,
            'Mengupdate user ID: ' . $userId,
            $request,
            ['user_id' => $userId, 'changes' => $changes]
        );
    }
    /**
     * Log aktivitas menghapus user
     */
    public static function logDeleteUser(int $userId, string $userTitle, ?Request $request = null): void
    {
        self::log(
            self::DELETE_USER,
            'Menghapus user: ' . $userTitle,
            $request,
            ['user_id' => $userId]
        );
    }
    /**
     * Log aktivitas update profile
     */
    public static function logProfileUpdate(array $changes, ?Request $request = null): void
    {
        self::log(
            self::PROFILE_UPDATE,
            'Mengupdate profile user',
            $request,
            ['changes' => array_keys($changes)]
        );
    }

    /**
     * Log akses tidak terotorisasi
     */
    public static function logUnauthorizedAccess(string $attemptedAction, ?Request $request = null): void
    {
        self::log(
            self::UNAUTHORIZED_ACCESS,
            'Akses tidak terotorisasi: ' . $attemptedAction,
            $request,
            null,
            'failed'
        );
    }

    /**
     * Mendapatkan statistik aktivitas
     */
    public static function getActivityStats(int $days = 7): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $today = Carbon::today();

        return [
            'total_logs' => self::getTotalLogs(), // Panggil method getTotalLogs()
            'today_logs' => ActivityLog::whereDate('created_at', $today)->count(),
            'login_success' => ActivityLog::where('activity_type', self::LOGIN_SUCCESS)
                ->where('created_at', '>=', $startDate)->count(),
            'login_failed' => ActivityLog::where('activity_type', self::LOGIN_FAILED)
                ->where('created_at', '>=', $startDate)->count(),
            'unauthorized_access' => ActivityLog::where('activity_type', self::UNAUTHORIZED_ACCESS)
                ->where('created_at', '>=', $startDate)->count(),
            'unique_users' => ActivityLog::where('created_at', '>=', $startDate)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count(),
            'unique_ips' => ActivityLog::where('created_at', '>=', $startDate)
                ->distinct('ip_address')
                ->count(),
        ];
    }

    /**
     * Mendapatkan statistik aktivitas hari ini
     */
    public static function getTodayStats(): array
    {
        $today = Carbon::today();

        return [
            'total_today' => ActivityLog::whereDate('created_at', $today)->count(),
            'login_success_today' => ActivityLog::where('activity_type', self::LOGIN_SUCCESS)
                ->whereDate('created_at', $today)->count(),
            'login_failed_today' => ActivityLog::where('activity_type', self::LOGIN_FAILED)
                ->whereDate('created_at', $today)->count(),
            'unique_users_today' => ActivityLog::whereDate('created_at', $today)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count(),
        ];
    }

    /**
     * Mendapatkan total semua logs
     */
    public static function getTotalLogs(): int
    {
        return ActivityLog::count();
    }

    /**
     * Test method untuk memastikan logging berfungsi
     */
    public static function testLogging(): array
    {
        try {
            // Test basic logging
            self::log('test', 'Testing activity log functionality');

            $totalLogs = self::getTotalLogs();
            $todayStats = self::getTodayStats();

            return [
                'status' => 'success',
                'total_logs' => $totalLogs,
                'today_logs' => $todayStats['total_today'],
                'message' => 'Test log created successfully'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Debug method untuk melihat struktur tabel
     */
    public static function debugTableStructure(): array
    {
        try {
            $table = (new ActivityLog())->getTable();

            // Check if table exists dengan cara yang lebih kompatibel
            $tableExists = \Illuminate\Support\Facades\Schema::hasTable($table);

            if (!$tableExists) {
                return [
                    'status' => 'error',
                    'message' => "Table '{$table}' does not exist. Please run migrations."
                ];
            }

            // Get column information
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);

            // Test insert capability
            $canInsert = true;
            $insertError = null;
            try {
                // Test dengan data dummy (tidak akan tersimpan karena menggunakan transaction)
                \Illuminate\Support\Facades\DB::beginTransaction();
                ActivityLog::create([
                    'activity_type' => 'test',
                    'description' => 'Test insert',
                    'status' => 'success',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                \Illuminate\Support\Facades\DB::rollback();
            } catch (\Exception $e) {
                $canInsert = false;
                $insertError = $e->getMessage();
                \Illuminate\Support\Facades\DB::rollback();
            }

            return [
                'status' => 'success',
                'table_name' => $table,
                'table_exists' => $tableExists,
                'columns' => $columns,
                'total_records' => ActivityLog::count(),
                'can_insert' => $canInsert,
                'insert_error' => $insertError
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
