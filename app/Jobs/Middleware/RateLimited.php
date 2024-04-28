<?php

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Support\Facades\Cache;

class RateLimited
{
    /**
     * Process the queued job.
     *
     * @see https://ykravchuk.medium.com/laravel-job-rate-limit-middleware-without-redis-using-cache-locks-8cedef829ab0
     *
     * @param object $job
     * @param \Closure(object): void $next
     * @throws LimiterTimeoutException
     */
    public function handle(object $job, Closure $next): void
    {
        // We need some identifier for a group of jobs
        // In case we want to apply the same cache lock for all jobs,
        // set the same group to all jobs
        $jobGroup = $job->getJobGroup();

        // Create a cache lock for 5 seconds
        $lock = Cache::lock($jobGroup, 5);

        // Trying to get a lock and fire a job (if 5 seconds passed)
        if ($lock->get()) {
            $next($job);
        }

        // Send a job back to the queue if the lock can't acquire
        $job->release();
    }
}
