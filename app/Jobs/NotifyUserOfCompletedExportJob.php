<?php

namespace App\Jobs;

use App\Contracts\FileRateLimitInterface;
use App\Jobs\Middleware\RateLimited;
use App\Models\User;
use App\Notifications\ExportReadyNotification;
use App\Services\Contracts\FileServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NotifyUserOfCompletedExportJob implements ShouldQueue, FileRateLimitInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User   $user,
        public string $path,
        public string $fullPath,
        public string $disk
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getJobGroup(): string
    {
        return "default";
    }

    /**
     * Execute the job.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): void
    {
        /**
         * @var FileServiceInterface $fileService
         */
        $fileService = app()->get(FileServiceInterface::class);
        $saved = $fileService->saveToDb(data: $this->fullPath, disk: $this->disk);

        if (!is_null($saved)) {
            $this->user->notify(new ExportReadyNotification($this->user, $this->path));
        }
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new RateLimited];
    }
}
