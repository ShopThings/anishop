<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;

trait UserTimezoneDetectorTrait
{
    /**
     * @return string
     */
    public function getUserTimezone(): string
    {
        // Determine the timezone based on the client's IP address
        $ipAddress = Request::ip();
        return $this->getTimezoneFromIpAddress($ipAddress);
    }

    /**
     * @param string $ipAddress
     * @return string
     */
    protected function getTimezoneFromIpAddress(string $ipAddress): string
    {
        // Define the rate limiting rules
        $rateLimitKey = 'ip-api-rate-limit:' . $ipAddress;
        $rateLimitMaxAttempts = 100;
        $rateLimitDecayMinutes = 1;

        // Check if the rate limit has been exceeded
        if (RateLimiter::tooManyAttempts($rateLimitKey, $rateLimitMaxAttempts)) {
            // Return a fallback timezone if the rate limit has been exceeded
            return 'UTC';
        }

        // Make the API request
        $response = Http::get("http://ip-api.com/json/{$ipAddress}");

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 'success' && isset($data['timezone'])) {
                // Increment the rate limit counter
                RateLimiter::hit($rateLimitKey, $rateLimitDecayMinutes * 60);
                return $data['timezone'];
            }
        }

        // If the API request fails or doesn't return a valid timezone, return a fallback timezone
        RateLimiter::hit($rateLimitKey, $rateLimitDecayMinutes * 60);
        return 'UTC';
    }
}
