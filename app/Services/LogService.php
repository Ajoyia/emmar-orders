<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogService
{
    public function logApiRequest(
        string $apiName,
        ?string $statusCode,
        mixed $request,
        mixed $response
    ): void {
        DB::table('logs')->insert([
            'api_name' => $apiName,
            'status_code' => $statusCode,
            'request' => is_array($request) ? json_encode($request) : $request,
            'response' => is_string($response) ? $response : json_encode($response),
            'created_at' => Carbon::now(),
        ]);
    }
}

