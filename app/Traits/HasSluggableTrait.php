<?php

namespace App\Traits;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait HasSluggableTrait
{
    use HasSlug;

    /**
     * @return string
     */
    protected function getSluggableField(): string
    {
        return $this?->sluggableField ?? 'name';
    }

    /**
     * @return string
     */
    protected function getSlugField(): string
    {
        return $this?->slugField ?? 'slug';
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSluggableField())
            ->saveSlugsTo($this->getSlugField());
    }
}
