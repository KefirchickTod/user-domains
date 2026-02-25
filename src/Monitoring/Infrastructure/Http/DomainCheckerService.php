<?php

declare(strict_types=1);

namespace Src\Monitoring\Infrastructure\Http;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class DomainCheckerService
{
    public function check(string $url, string $method, int $timeout_seconds): array
    {
        $start_time = microtime(true);

        try {
            $response = Http::timeout($timeout_seconds)
                ->send($method, $url);

            $response_time_ms = (int) round((microtime(true) - $start_time) * 1000);
            $status_code      = $response->status();
            $is_successful    = $response->successful();

            return [
                'status_code'      => $status_code,
                'response_time_ms' => $response_time_ms,
                'is_successful'    => $is_successful,
                'error_message'    => $is_successful ? null : "HTTP {$status_code}",
            ];
        } catch (ConnectionException $e) {
            $response_time_ms = (int) round((microtime(true) - $start_time) * 1000);

            return [
                'status_code'      => null,
                'response_time_ms' => $response_time_ms,
                'is_successful'    => false,
                'error_message'    => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            return [
                'status_code'      => null,
                'response_time_ms' => null,
                'is_successful'    => false,
                'error_message'    => $e->getMessage(),
            ];
        }
    }
}
