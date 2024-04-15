<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
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
        $this->policyModel = Complaint::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Complaint::class);
        return ComplaintResource::collection($this->service->getComplaints($filter));
    }

    /**
     * Display the specified resource.
     *
     * @param Complaint $complaint
     * @return ComplaintResource
     */
    public function show(Complaint $complaint): ComplaintResource
    {
        Gate::authorize('view', $complaint);
        return new ComplaintResource($complaint);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateComplaintRequest $request
     * @param Complaint $complaint
     * @return JsonResponse|ComplaintResource
     */
    public function update(
        UpdateComplaintRequest $request,
        Complaint              $complaint
    ): JsonResponse|ComplaintResource
    {
        Gate::authorize('update', $complaint);

        $validated = $request->validated(['is_seen']);
        $model = $this->service->updateById($complaint->id, $validated);

        if (!is_null($model)) {
            return new ComplaintResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش شکایت',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Complaint $complaint
     * @return JsonResponse
     */
    public function destroy(Request $request, Complaint $complaint): JsonResponse
    {
        Gate::authorize('delete', $complaint);

        $permanent = $request->user()->id === $complaint->creator?->id;
        $res = $this->service->deleteById($complaint->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
