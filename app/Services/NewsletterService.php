<?php

namespace App\Services;

use App\Repositories\Contracts\NewsletterRepositoryInterface;
use App\Services\Contracts\NewsletterServiceInterface;
use App\Support\Service;

class NewsletterService extends Service implements NewsletterServiceInterface
{
    public function __construct(protected NewsletterRepositoryInterface $repository)
    {

    }
}
