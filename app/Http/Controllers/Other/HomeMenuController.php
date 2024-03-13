<?php

namespace App\Http\Controllers\Other;

use App\Enums\Menus\MenuPlacesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\MenuResource as HomeMenuResource;
use App\Services\Contracts\MenuServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class HomeMenuController extends Controller
{
    /**
     * @param MenuServiceInterface $service
     */
    public function __construct(
        protected MenuServiceInterface $service
    )
    {
    }

    /**
     * @param string $menu
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function menu(string $menu): JsonResponse|AnonymousResourceCollection
    {
        $placeIn = MenuPlacesEnum::tryFrom($menu);

        if (is_null($placeIn)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'منوی درخواست شده وجود ندارد.',
            ], ResponseCodes::HTTP_NOT_FOUND);
        }

        return HomeMenuResource::collection($this->service->getHomeMenus($placeIn));
    }
}
