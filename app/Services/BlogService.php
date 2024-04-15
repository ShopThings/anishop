<?php

namespace App\Services;

use App\Enums\Blogs\BlogOrderTypesEnum;
use App\Enums\Blogs\BlogVotingTypesEnum;
use App\Enums\DatabaseEnum;
use App\Enums\Settings\SettingsEnum;
use App\Enums\Sliders\SliderItemOptionsEnum;
use App\Enums\Sliders\SliderPlacesEnum;
use App\Http\Requests\Filters\HomeBlogFilter;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Services\Contracts\SliderServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BlogService extends Service implements BlogServiceInterface
{
    use ImageFieldTrait;

    public function __construct(
        protected BlogRepositoryInterface      $repository,
        protected SettingServiceInterface      $settingService,
        protected SliderServiceInterface       $sliderService,
        protected BlogCategoryServiceInterface $blogCategoryService,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getBlogs(Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getBlogsSearchFilterPaginated(filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getSingleBlog(GetterExpressionInterface $where): ?Model
    {
        if (trim($where->getStatement()) === '') return null;
        return $this->repository->findWhere($where);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredBlogs(HomeBlogFilter $filter): Collection|LengthAwarePaginator
    {
        $settingModel = $this->settingService->getSetting(SettingsEnum::BLOG_EACH_PAGE->value);
        $limit = $settingModel->setting_value ?: $settingModel->default_value;

        $filter->setLimit($limit);

        return $this->getBlogs($filter);
    }

    /**
     * @inheritDoc
     */
    public function toggleVote(int $userId, int $blogId, BlogVotingTypesEnum $vote): bool
    {
        return $this->repository->toggleBlogVote($userId, $blogId, $vote);
    }

    /**
     * @inheritDoc
     */
    public function getArchive(): Collection
    {
        return $this->repository->getArchive();
    }

    /**
     * @inheritDoc
     */
    public function getMainSlider(): Collection
    {
        $slider = $this->sliderService->getSlider(SliderPlacesEnum::MAIN_BLOG)->first();
        if (null === $slider) return collect();

        return $this->_getMainSlides($slider);
    }

    /**
     * @inheritDoc
     */
    public function getMainSideSlides(): Collection
    {
        $slider = $this->sliderService->getSlider(SliderPlacesEnum::MAIN_BLOG_SIDE)->first();
        if (null === $slider) return collect();

        return $this->_getMainSlides($slider);
    }

    /**
     * @inheritDoc
     */
    public function getLatestMostViewedBlogs(int $limit = 10): Collection
    {
        $filter = new HomeBlogFilter(request());
        $filter->reset()->setBlogOrder(BlogOrderTypesEnum::MOST_VIEWED)->setLimit($limit);

        return collect($this->getBlogs($filter)->items());
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attributes['image'] = $this->getImageId($attributes['image'] ?? null);

        $attrs = [
            'category_id' => $attributes['category'],
            'title' => $attributes['title'],
            'escaped_title' => NumberConverter::toEnglish($attributes['title']),
            'image_id' => $attributes['image'],
            'description' => $attributes['description'],
            'keywords' => $attributes['keywords'],
            'is_commenting_allowed' => to_boolean($attributes['is_commenting_allowed']),
            'is_published' => to_boolean($attributes['is_published']),
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['category'])) {
            $updateAttributes['category_id'] = $attributes['category'];
        }
        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
            $updateAttributes['escaped_title'] = NumberConverter::toEnglish($attributes['title']);
        }
        if (isset($attributes['image'])) {
            $attributes['image'] = $this->getImageId($attributes['image'] ?? null);
            $updateAttributes['image_id'] = $attributes['image'];
        }
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }
        if (isset($attributes['keywords'])) {
            $updateAttributes['keywords'] = $attributes['keywords'];
        }
        if (isset($attributes['is_commenting_allowed'])) {
            $updateAttributes['is_commenting_allowed'] = to_boolean($attributes['is_commenting_allowed']);
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * @param Model $slider
     * @return Collection
     */
    private function _getMainSlides(Model $slider): Collection
    {
        /**
         * @var Collection $ids
         */
        $ids = $slider->items->pluck('options')->pluck(SliderItemOptionsEnum::BLOG_ID->value);

        // get all blogs with slides ids
        $where = new WhereBuilder('blogs');
        $where->whereIn('id', $ids->toArray())
            ->whereEqual('is_published', DatabaseEnum::DB_YES);
        return $this->repository->all(where: $where->build());
    }
}
