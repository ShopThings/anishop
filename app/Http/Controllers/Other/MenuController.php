<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Models\User;
use App\Services\Contracts\MenuServiceInterface;
use App\Support\Filter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
