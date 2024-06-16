<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\StoreContactUsRequest;
use App\Http\Requests\StoreNewsletterRequest;
use App\Http\Resources\Home\FaqResource as HomeFaqResource;
use App\Http\Resources\SettingResource;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Services\Contracts\FaqServiceInterface;
use App\Services\Contracts\NewsletterServiceInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class HomeController extends Controller
{
    /**
     * @param SettingServiceInterface $service
     * @return AnonymousResourceCollection
     */
    public function settings(SettingServiceInterface $service): AnonymousResourceCollection
    {
        $settings = $service->getGeneralSettings();
        return SettingResource::collection($settings);
    }

    /**
     * @param StoreContactUsRequest $request
     * @param ContactUsServiceInterface $service
     * @return JsonResponse
     */
    public function storeContactUs(
        StoreContactUsRequest     $request,
        ContactUsServiceInterface $service
    ): JsonResponse
    {
        $model = $service->create($request->validated());

        if (null !== $model) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => 'پیام شما ثبت شد.',
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت پیام',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param StoreComplaintRequest $request
     * @param ComplaintServiceInterface $service
     * @return JsonResponse
     */
    public function storeComplaint(
        StoreComplaintRequest     $request,
        ComplaintServiceInterface $service
    ): JsonResponse
    {
        $model = $service->create($request->validated());

        if (null !== $model) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => 'شکایت شما ثبت شد.',
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت شکایت',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param StoreNewsletterRequest $request
     * @param NewsletterServiceInterface $service
     * @return JsonResponse
     */
    public function storeNewsletter(
        StoreNewsletterRequest     $request,
        NewsletterServiceInterface $service
    ): JsonResponse
    {
        $model = $service->create($request->validated());

        if (null !== $model) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => 'شماره شما برای ارسال خبرنامه ثبت شد.',
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت شماره در خبرنامه',
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Filter $filter
     * @param FaqServiceInterface $service
     * @return AnonymousResourceCollection
     */
    public function faqs(Filter $filter, FaqServiceInterface $service): AnonymousResourceCollection
    {
        $filter->setLimit(null);
        return HomeFaqResource::collection($service->getHomeFaqs($filter));
    }
}
