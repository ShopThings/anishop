<?php

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Support\Facades\Redis;

class RateLimited
{
    /**
     * Process the queued job.
     *
     * @param object $job
     * @param \Closure(object): void $next
     * @throws LimiterTimeoutException
     */
    public function handle(object $job, Closure $next): void
    {
        Redis::throttle('runningJob')
            ->block(0)->allow(1)->every(5)
            ->then(function () use ($job, $next) {
                // Lock obtained...

                $next($job);
            }, function () use ($job) {
                // Could not obtain lock...

                $job->release(5);
            });
    }
}
