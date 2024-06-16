<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderItemRequest;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Http\Resources\SliderItemResource;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use App\Services\Contracts\SliderServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class SliderController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param SliderServiceInterface $service
     */
    public function __construct(
        protected SliderServiceInterface $service
    )
    {
        $this->considerDeletable = true;
        $this->policyModel = Slider::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Slider::class);
        return SliderResource::collection($this->service->getSliders($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSliderRequest $request
     * @return JsonResponse
     */
    public function store(StoreSliderRequest $request): JsonResponse
    {
        Gate::authorize('create', Slider::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد اسلایدر با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد اسلایدر',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Slider $slider
     * @return SliderResource
     */
    public function show(Slider $slider): SliderResource
    {
        Gate::authorize('view', $slider);
        return new SliderResource($slider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSliderRequest $request
     * @param Slider $slider
     * @return SliderResource|JsonResponse
     */
    public function update(UpdateSliderRequest $request, Slider $slider): SliderResource|JsonResponse
    {
        Gate::authorize('update', $slider);

        $validated = filter_validated_data($request->validated(), [
            'slider_place',
            'title',
            'priority',
            'options',
            'is_published',
        ]);
        $model = $this->service->updateById($slider->id, $validated);

        if (!is_null($model)) {
            return new SliderResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش اسلایدر',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Slider $slider
     * @return JsonResponse
     */
    public function destroy(Request $request, Slider $slider): JsonResponse
    {
        Gate::authorize('delete', $slider);

        $permanent = $request->user()->id === $slider->creator?->id;
        $res = $this->service->deleteById($slider->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Slider $slider
     * @return AnonymousResourceCollection
     */
    public function showSlides(Slider $slider): AnonymousResourceCollection
    {
        Gate::authorize('view', $slider);
        return SliderItemResource::collection($this->service->getSliderItems($slider));
    }

    /**
     * @param StoreSliderItemRequest $request
     * @param Slider $slider
     * @return AnonymousResourceCollection
     */
    public function modifySlides(StoreSliderItemRequest $request, Slider $slider): AnonymousResourceCollection
    {
        Gate::authorize('create', Slider::class);

        $validated = $request->validated();
        return SliderItemResource::collection($this->service->modifySliderItems($slider->id, $validated['slides']));
    }
}
