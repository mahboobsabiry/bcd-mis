<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function getStats()
    {
        $memoryUsage = number_format(memory_get_usage() / 1024 / 1024, 2);
        $responseTime = round((microtime(true) - LARAVEL_START) * 1000, 2);

        // Calculate disk usage
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        $diskUsed = $diskTotal - $diskFree;
        $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 2) : 0;

        return response()->json([
            'memory_usage' => $memoryUsage,
            'response_time' => $responseTime,
            'disk_usage' => $diskPercent,
            'online_users' => \App\Models\User::online()->count(),
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}
