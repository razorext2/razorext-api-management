<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // 1. Extract API Key from Header (X-API-KEY or Bearer token)
        $apiKey = $request->header('X-API-KEY')
            ?? $request->header('x-api-key')
            ?? $request->bearerToken();

        if (empty($apiKey)) {
            return $this->errorResponse('API key is missing. Please provide X-API-KEY header.', 401, $startTime);
        }

        // 2. Lookup Client
        $client = ApiClient::where('api_key', $apiKey)->first();

        if (! $client) {
            return $this->errorResponse('Invalid API key provided.', 401, $startTime);
        }

        if (! $client->is_active) {
            return $this->errorResponse('API client account is deactivated. Contact administrator.', 403, $startTime, $client->id);
        }

        // 3. IP Whitelist check (if configured)
        if (! empty($client->allowed_ips)) {
            $clientIp = $request->ip();
            if (! in_array($clientIp, $client->allowed_ips, true)) {
                return $this->errorResponse("IP address [{$clientIp}] is not authorized for this API key.", 403, $startTime, $client->id);
            }
        }

        // 4. Rate Limiting per Client
        $rateLimitKey = 'api-client:' . $client->id;
        $maxAttempts = $client->rate_limit_per_minute ?: 60;

        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return $this->errorResponse("Rate limit exceeded. Try again in {$seconds} seconds.", 429, $startTime, $client->id);
        }

        RateLimiter::hit($rateLimitKey, 60);

        // 5. Update last_used_at asynchronously/quietly
        $client->timestamps = false;
        $client->update(['last_used_at' => now()]);
        $client->timestamps = true;

        // Attach client to request
        $request->attributes->set('api_client', $client);

        // 6. Process request
        /** @var Response $response */
        $response = $next($request);

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        // 7. Log API Request
        $this->logRequest($client->id, $request, $response->getStatusCode(), $executionTime);

        // Add telemetry headers
        $response->headers->set('X-Execution-Time-Ms', (string) $executionTime);
        $response->headers->set('X-RateLimit-Limit', (string) $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', (string) RateLimiter::remaining($rateLimitKey, $maxAttempts));

        return $response;
    }

    protected function errorResponse(string $message, int $statusCode, float $startTime, ?int $clientId = null): JsonResponse
    {
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        if ($clientId) {
            $this->logRequest($clientId, request(), $statusCode, $executionTime, $message);
        }

        return response()->json([
            'success' => false,
            'status' => $statusCode,
            'message' => $message,
            'execution_time_ms' => $executionTime,
        ], $statusCode);
    }

    protected function logRequest(?int $clientId, Request $request, int $statusCode, float $executionTime, ?string $errorMessage = null): void
    {
        try {
            ApiRequestLog::create([
                'api_client_id' => $clientId,
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'status_code' => $statusCode,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'execution_time_ms' => $executionTime,
                'error_message' => $errorMessage,
                'created_at' => now(),
            ]);
        } catch (\Throwable) {
            // Silently ignore logging failures to avoid breaking request flow
        }
    }
}
