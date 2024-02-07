<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Models\User;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ComplaintController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param ComplaintServiceInterface $service
     */
    public function __construct(
        protected ComplaintServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return ComplaintResource::collection($this->service->getComplaints($filter));
    }

    /**
     * Display the specified resource.
     *
     * @param Complaint $complaint
     * @return ComplaintResource
     * @throws AuthorizationException
     */
    public function show(Complaint $complaint): ComplaintResource
    {
        $this->authorize('view', $complaint);
        return new ComplaintResource($complaint);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComplaintResource $request, Complaint $complaint): JsonResponse|ComplaintResource
    {
        $this->authorize('update', $complaint);

        $validated = $request->validated(['is_seen']);
        $model = $this->service->updateById($complaint->id, $validated);

        if (!is_null($model)) {
            return new ComplaintResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش شکایت',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Complaint $complaint
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Complaint $complaint): JsonResponse
    {
        $this->authorize('delete', $complaint);

        $permanent = $request->user()->id === $complaint->creator?->id;
        $res = $this->service->deleteById($complaint->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
