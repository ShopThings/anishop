<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuItemResource;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Services\Contracts\MenuServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
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
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Menu::class);
        return MenuResource::collection($this->service->getMenus($filter));
    }

    /**
     * Display the specified resource.
     *
     * @param Menu $menu
     * @return MenuResource
     */
    public function show(Menu $menu): MenuResource
    {
        Gate::authorize('view', $menu);
        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMenuRequest $request
     * @param Menu $menu
     * @return MenuResource|JsonResponse
     */
    public function update(UpdateMenuRequest $request, Menu $menu): JsonResponse|MenuResource
    {
        Gate::authorize('update', $menu);

        $validated = $request->validated();
        $model = $this->service->updateById($menu->id, [
            'title' => $validated['title'],
            'is_published' => $validated['is_published'],
        ]);

        if (!is_null($model)) {
            return new MenuResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش منو',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Menu $menu
     * @return AnonymousResourceCollection
     */
    public function showItems(Menu $menu): AnonymousResourceCollection
    {
        Gate::authorize('view', $menu);
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
     */
    public function modifyMenus(StoreMenuItemRequest $request, Menu $menu): AnonymousResourceCollection
    {
        Gate::authorize('view', $menu);

        $validated = $request->validated();
        return MenuItemResource::collection($this->service->modifyMenuItems($menu->id, $validated['menus']));
    }
}
