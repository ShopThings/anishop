<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\PageResource;
use App\Models\StaticPage;
use Illuminate\Http\JsonResponse;

class HomePageController extends Controller
{
    /**
     * @param StaticPage $page
     * @return JsonResponse|PageResource
     */
    public function show(StaticPage $page): JsonResponse|PageResource
    {
        if (!$page->is_published) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'صفحه مورد نظر وجود ندارد.',
            ]);
        }

        return new PageResource($page);
    }
}
