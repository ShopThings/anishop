<?php

namespace App\Http\Requests\Filters;

use App\Enums\Blogs\BlogOrderTypesEnum;
use App\Support\Converters\NumberConverter;
use App\Support\Filter;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class HomeBlogFilter extends Filter
{
    /**
     * @var array|string|null
     */
    protected array|string|null $tag = null;

    /**
     * @var int|null
     */
    protected ?int $category = null;

    /**
     * @var string|null
     */
    protected ?string $archive = null;

    /**
     * @var BlogOrderTypesEnum
     */
    protected BlogOrderTypesEnum $blogOrder = BlogOrderTypesEnum::NEWEST;

    /**
     * @return string|null
     */
    public function getTag(): array|string|null
    {
        return $this->tag;
    }

    /**
     * @param string|null $tag
     * @return static
     */
    public function setTag(array|string|null $tag): static
    {
        if (null !== $tag) $this->tag = is_string($tag)
            ? trim($tag)
            : array_filter($tag, fn($item) => is_string($item) && trim($item) !== '');
        else $this->tag = null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategory(): ?int
    {
        return $this->category;
    }

    /**
     * @param int|null $category
     * @return static
     */
    public function setCategory(?int $category): static
    {
        $this->category = $category && $category >= 1 ? $category : null;
        return $this;
    }

    /**
     * @param bool $raw
     * @return Carbon|string|null
     */
    public function getArchive(bool $raw = false): Carbon|string|null
    {
        if ($raw || null === $this->archive) return $this->archive;
        try {
            return vertaTz(Verta::parse($this->archive))->toCarbon();
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * @param string|null $archive
     * @return static
     */
    public function setArchive(?string $archive): static
    {
        $archive = NumberConverter::toEnglish($archive);
        if (!!preg_match('/^\d{4}-\d{0,2}$/', $archive)) {
            $this->archive = $archive;
        }
        return $this;
    }

    /**
     * @return BlogOrderTypesEnum
     */
    public function getBlogOrder(): BlogOrderTypesEnum
    {
        return $this->blogOrder;
    }

    /**
     * @param BlogOrderTypesEnum|null $blogOrder
     * @return static
     */
    public function setBlogOrder(?BlogOrderTypesEnum $blogOrder): static
    {
        if (null !== $blogOrder) $this->blogOrder = $blogOrder;
        else $this->blogOrder = BlogOrderTypesEnum::NEWEST;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->tag = null;
        $this->category = null;
        $this->archive = null;
        $this->blogOrder = BlogOrderTypesEnum::NEWEST;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $this->setCategory($request->integer('category'));
        $this->setArchive($request->string('archive')->toString());
        $this->setBlogOrder($request->enum('order', BlogOrderTypesEnum::class));

        // get tags
        $tags = $request->input('tag');
        if (is_string($tags) || is_array($tags)) {
            $this->setTag($tags);
        }
    }
}
