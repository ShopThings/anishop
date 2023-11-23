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
use App\Models\User;
use App\Services\Contracts\SliderServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class SliderController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param SliderServiceInterface $service
     */
    public function __construct(
        protected SliderServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return SliderResource::collection($this->service->getSliders(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSliderRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreSliderRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد اسلایدر با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد اسلایدر',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Slider $slider
     * @return SliderResource
     * @throws AuthorizationException
     */
    public function show(Slider $slider)
    {
        $this->authorize('view', $slider);
        return new SliderResource($slider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSliderRequest $request
     * @param Slider $slider
     * @return SliderResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        $this->authorize('update', $slider);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($slider->id, $validated);

        if (!is_null($model)) {
            return new SliderResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش اسلایدر',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Slider $slider
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Slider $slider)
    {
        $this->authorize('delete', $slider);

        $permanent = $request->user()->id === $slider->creator()?->id;
        $res = $this->service->deleteById($slider->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param StoreSliderItemRequest $request
     * @param Slider $slider
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function modifySlides(StoreSliderItemRequest $request, Slider $slider)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        return SliderItemResource::collection($this->service->modifySliderItems($slider->id, $validated['items']));
    }
}
