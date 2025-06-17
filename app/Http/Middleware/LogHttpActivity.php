<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogHttpActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log hanya untuk request tertentu
        if ($this->shouldLog($request)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Tentukan apakah request harus di-log
     */
    private function shouldLog(Request $request): bool
    {
        // Jangan log request untuk asset atau file statis
        $excludedPaths = [
            'css',
            'js', 
            'images',
            'fonts',
            'favicon.ico',
            'robots.txt',
            '_debugbar',
            'storage'
        ];

        $path = $request->path();
        
        foreach ($excludedPaths as $excluded) {
            if (str_starts_with($path, $excluded)) {
                return false;
            }
        }

        // Log hanya untuk method tertentu
        $loggedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        
        return in_array($request->method(), $loggedMethods) || 
               $this->isImportantGetRequest($request);
    }

    /**
     * Tentukan apakah GET request penting untuk di-log
     */
    private function isImportantGetRequest(Request $request): bool
    {
        $importantPaths = [
            'admin',
            'dashboard', 
            'profile',
            'header',
            'settings'
        ];

        $path = $request->path();
        
        foreach ($importantPaths as $important) {
            if (str_contains($path, $important)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log aktivitas berdasarkan request
     */
    private function logActivity(Request $request, Response $response): void
    {
        $path = $request->path();
        $method = $request->method();
        $activityType = $this->determineActivityType($request);
        $description = $this->generateDescription($request);

        // Tentukan status berdasarkan response code
        $status = $response->getStatusCode() >= 400 ? 'failed' : 'success';
        
        // Error message jika ada
        $errorMessage = null;
        if ($status === 'failed') {
            $errorMessage = "HTTP {$response->getStatusCode()}: {$this->getHttpStatusText($response->getStatusCode())}";
        }

        ActivityLogService::log(
            $activityType,
            $description,
            $request,
            null,
            $status,
            $errorMessage
        );
    }

    /**
     * Tentukan tipe aktivitas berdasarkan request
     */
    private function determineActivityType(Request $request): string
    {
        $path = $request->path();
        $method = $request->method();

        // Mapping berdasarkan path dan method
        $pathMappings = [
            'header' => [
                'GET' => ActivityLogService::VIEW_HEADER,
                'POST' => ActivityLogService::CREATE_HEADER,
                'PUT' => ActivityLogService::UPDATE_HEADER,
                'PATCH' => ActivityLogService::UPDATE_HEADER,
                'DELETE' => ActivityLogService::DELETE_HEADER,
            ],
            'profile' => [
                'GET' => 'view_profile',
                'PUT' => ActivityLogService::PROFILE_UPDATE,
                'PATCH' => ActivityLogService::PROFILE_UPDATE,
            ],
            'dashboard' => [
                'GET' => 'view_dashboard',
            ],
            'admin' => [
                'GET' => 'admin_access',
            ]
        ];

        foreach ($pathMappings as $pathKey => $methods) {
            if (str_contains($path, $pathKey)) {
                return $methods[$method] ?? 'unknown_activity';
            }
        }

        return 'http_request';
    }

    /**
     * Generate deskripsi aktivitas
     */
    private function generateDescription(Request $request): string
    {
        $path = $request->path();
        $method = $request->method();

        if (str_contains($path, 'header')) {
            switch ($method) {
                case 'GET':
                    return 'Mengakses halaman header';
                case 'POST':
                    return 'Membuat header baru';
                case 'PUT':
                case 'PATCH':
                    return 'Mengupdate header';
                case 'DELETE':
                    return 'Menghapus header';
            }
        }

        if (str_contains($path, 'profile')) {
            return $method === 'GET' ? 'Mengakses halaman profile' : 'Mengupdate profile';
        }

        if (str_contains($path, 'dashboard')) {
            return 'Mengakses dashboard';
        }

        if (str_contains($path, 'admin')) {
            return 'Mengakses halaman admin';
        }

        return "{$method} request ke {$path}";
    }

    /**
     * Get HTTP status text
     */
    private function getHttpStatusText(int $statusCode): string
    {
        $statusTexts = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
        ];

        return $statusTexts[$statusCode] ?? 'Unknown Status';
    }
}