<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuItemResource;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Models\User;
use App\Services\Contracts\MenuServiceInterface;
use App\Support\Filter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class MenuController extends Controller
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
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return MenuResource::collection($this->service->getMenus($filter));
    }

    /**
     * Display the specified resource.
     *
     * @param Menu $menu
     * @return MenuResource
     * @throws AuthorizationException
     */
    public function show(Menu $menu): MenuResource
    {
        $this->authorize('view', $menu);
        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMenuRequest $request
     * @param Menu $menu
     * @return MenuResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateMenuRequest $request, Menu $menu): JsonResponse|MenuResource
    {
        $this->authorize('update', $menu);

        $validated = $request->validated();
        $model = $this->service->updateById($menu->id, [
            'title' => $validated['title'],
            'is_published' => $validated['is_published'],
        ]);

        if (!is_null($model)) {
            return new MenuResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش منو',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param Menu $menu
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function showItems(Menu $menu): AnonymousResourceCollection
    {
        $this->authorize('view', $menu);
        return MenuItemResource::collection(
            $menu->items()
                ->with([
                    'parent',
                    'children',
                ])
                ->orderBy('priority')
                ->orderBy('id')
                ->get()
        );
    }

    /**
     * @param StoreMenuItemRequest $request
     * @param Menu $menu
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function modifyMenus(StoreMenuItemRequest $request, Menu $menu): AnonymousResourceCollection
    {
        $this->authorize('view', $menu);

        $validated = $request->validated();
        return MenuItemResource::collection($this->service->modifyMenuItems($menu->id, $validated['menus']));
    }
}
