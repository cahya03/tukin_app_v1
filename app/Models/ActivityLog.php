<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_type',
        'description',
        'user_id',
        'user_email',
        'ip_address',
        'user_agent',
        'request_url',
        'http_method',
        'request_data',
        'response_data',
        'status', 
        'error_message',
        'session_id'
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter berdasarkan tipe aktivitas
    public function scopeByActivity($query, string $activityType)
    {
        return $query->where('activity_type', $activityType);
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk filter berdasarkan IP
    public function scopeByIp($query, string $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    // Scope untuk filter berdasarkan rentang waktu
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Scope untuk log hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Method untuk mendapatkan aktivitas login yang gagal
    public static function getFailedLogins($limit = 10)
    {
        return self::byActivity('login_failed')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Method untuk mendapatkan aktivitas user tertentu
    public static function getUserActivity($userId, $limit = 20)
    {
        return self::byUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Method untuk mendapatkan aktivitas berdasarkan IP yang mencurigakan
    public static function getSuspiciousActivity($ipAddress, $timeFrame = '1 hour')
    {
        return self::byIp($ipAddress)
            ->where('created_at', '>=', now()->sub($timeFrame))
            ->get();
    }
}