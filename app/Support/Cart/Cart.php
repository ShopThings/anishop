<?php

namespace App\Support\Cart;

use App\Exceptions\CartException;
use App\Exceptions\InvalidCartNameException;
use App\Models\User;
use App\Services\Contracts\CartServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Support\Cart\Filters\CartFilterInterface;
use App\Support\Cart\Filters\CheckCountingFilter;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class Cart implements Arrayable, Jsonable
{
    /**
     * @var string
     */
    private string $instance;

    /**
     * @var Collection
     */
    private Collection $items;

    /**
     * @var array
     */
    private array $addFilters = [];

    /**
     * @var CartCalculations|null
     */
    private ?CartCalculations $calculations = null;

    /**
     * @param string|null $instance
     * @param User|null $user
     * @param CartServiceInterface $cartService
     * @param ProductServiceInterface $productService
     * @throws InvalidCartNameException
     */
    public function __construct(
        ?string                           $instance,
        protected ?User                   $user,
        protected CartServiceInterface    $cartService,
        protected ProductServiceInterface $productService
    )
    {
        if (!is_null($instance)) {
            $this->checkCartName($instance);
            $this->instance = $instance;
        } else {
            $this->instance = $this->getDefaultCartName();
        }

        $this->items = new Collection();

        $this->withAddFilters(new CheckCountingFilter());
    }

    /**
     * @param string $cartName
     * @return void
     * @throws InvalidCartNameException
     */
    protected function checkCartName(string $cartName): void
    {
        $cartNames = self::getCartNames();
        if (empty(trim($cartName)) || !in_array($cartName, array_values($cartNames))) {
            throw new InvalidCartNameException();
        }
    }

    /**
     * @return array
     */
    protected function getCartNames(): array
    {
        return config('market.cart_name', []);
    }

    /**
     * @return string|null
     */
    protected function getDefaultCartName(): ?string
    {
        return config('market.cart_name.default');
    }

    /**
     * @param array|CartFilterInterface $filers
     * @return static
     */
    public function withAddFilters(array|CartFilterInterface $filers): static
    {
        $filters = Arr::wrap($filers);
        $filters = array_filter($filters, fn($filter) => $filter instanceof CartFilterInterface);

        $this->addFilters = $filters;

        return $this;
    }

    /**
     * @param string|null $instance
     * @param User|null $user
     * @return static
     * @throws BindingResolutionException
     */
    public static function instance(?string $instance = null, ?User $user = null): static
    {
        return app()->make(static::class, [
            'instance' => $instance,
            'user' => $user,
        ]);
    }

    /**
     * @param User $user
     * @return static
     */
    public function ownedBy(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return CartCalculations
     */
    public function calculations(): CartCalculations
    {
        if (is_null($this->calculations)) {
            $this->calculations = new CartCalculations($this);
        }

        return $this->calculations;
    }

    /**
     * @return Collection
     */
    public function getContent(): Collection
    {
        return $this->items;
    }

    /**
     * @return static
     */
    public function changeToDefaultCart(): static
    {
        $this->instance = $this->getDefaultCartName();
        return $this;
    }

    /**
     * @return static
     */
    public function changeToWishlistCart(): static
    {
        $this->instance = $this->getWishlistCartName();
        return $this;
    }

    /**
     * @return string|null
     */
    protected function getWishlistCartName(): ?string
    {
        return config('market.cart_name.wishlist');
    }

    /**
     * @param string $code
     * @param int $quantity
     * @return bool
     */
    public function add(string $code, int $quantity = 1): bool
    {
        $item = $this->getValidatedItem($code);
        return $this->addItem($item, $quantity);
    }

    /**
     * @param string $code
     * @return Model|null
     */
    protected function getValidatedItem(string $code): ?Model
    {
        return $this->productService->getProductVariantByCode($code);
    }

    /**
     * @param Model|null $item
     * @param int $quantity
     * @return bool
     */
    protected function addItem(?Model $item, int $quantity): bool
    {
        if (is_null($item)) return false;

        $canAdd = true;
        /**
         * @var CartFilterInterface $filter
         */
        foreach ($this->addFilters as $filter) {
            if (!$filter->validate($item, $quantity, $this)) {
                $canAdd = false;
                break;
            }
        }

        if ($canAdd) {
            $this->items->add(new CartItem($item, $quantity));
        }

        return $canAdd;
    }

    /**
     * @param array|Collection $items
     * @param bool $loadInCurrentInstance
     * @return Collection|static
     */
    public function validate(array|Collection $items, bool $loadInCurrentInstance = false): Collection|static
    {
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // if items is empty just use empty collection
        if ($items->isEmpty()) {
            if ($loadInCurrentInstance) {
                $this->items = new Collection();
                return $this;
            }

            return new Collection();
        }

        $itemCodes = $items->pluck('code')->unique()->toArray();
        $validatedItems = $this->getValidatedItems($itemCodes);

        if ($loadInCurrentInstance) {
            $this->items = $validatedItems;
            return $this;
        }

        return $validatedItems;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $items = $this->items->map(function ($item) {
            if (!$item instanceof CartItem) {
                throw new CartException('خطا در بررسی محصولات وارده شده');
            }

            return $item->toArray();
        });

        return $items->toArray();
    }

    /**
     * @param array $codes
     * @return Collection
     */
    protected function getValidatedItems(array $codes): Collection
    {
        $validatedItems = new Collection();
        $productVariants = $this->productService->getProductVariantsByCodes($codes);

        $productVariants = $productVariants->filter(function ($item) {
            return $item->isAvailableForCart();
        });

        $productVariants->each(function ($product) use (&$validatedItems) {
            // Add the product variant to array
            $validatedItems->add($product);
        });

        return $validatedItems;
    }

    /**
     * @param array $mappedCodeQuantity
     * @return static
     */
    public function addAll(array $mappedCodeQuantity): static
    {
        $codes = array_keys($mappedCodeQuantity);
        $items = $this->getValidatedItems($codes);
        $items->each(function ($item) use ($mappedCodeQuantity) {
            $qty = intval($mappedCodeQuantity[$item->code] ?? 1);
            $this->addItem($item, $qty);
        });

        return $this;
    }

    /**
     * @param int $id
     * @return static
     */
    public function remove(int $id): static
    {
        $this->items = $this->items->reject(fn($item) => $item->id === $id);
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return static
     */
    public function removeByKey(string $key, string $value): static
    {
        $this->items = $this->items->reject(fn($item) => $item?->{$key} === $value);
        return $this;
    }

    /**
     * @return static
     */
    public function removeAll(): static
    {
        $this->items = new Collection();
        return $this;
    }

    /**
     * @return bool
     */
    public function store(): bool
    {
        $this->checkUser();

        if ($this->isDefaultCartName()) {
            return $this->cartService->saveDefaultCart($this->user, $this->toArray());
        } elseif ($this->isWishlistCartName()) {
            return $this->cartService->saveWishlistCart($this->user, $this->toArray());
        }

        return false;
    }

    /**
     * @return void
     */
    protected function checkUser(): void
    {
        if (is_null($this->user)) {
            throw new InvalidArgumentException('Please specify the user to do the operation.');
        }
    }

    /**
     * @return bool
     */
    protected function isDefaultCartName(): bool
    {
        return $this->instance === $this->getDefaultCartName();
    }

    /**
     * @return bool
     */
    protected function isWishlistCartName(): bool
    {
        return $this->instance === $this->getWishlistCartName();
    }

    /**
     * @param bool $loadInCurrentInstance
     * @return Collection|static
     */
    public function restore(bool $loadInCurrentInstance = false): Collection|static
    {
        $this->checkUser();

        $items = new Collection();
        if ($this->isDefaultCartName()) {
            $items = $this->cartService->getUserDefaultCart($this->user);
        } elseif ($this->isWishlistCartName()) {
            $items = $this->cartService->getUserWishlistCart($this->user);
        }

        $this->validate($items);

        if ($loadInCurrentInstance) {
            $this->items = $items;
            return $this;
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public function restoreAll(): Collection
    {
        $this->checkUser();

        $userCarts = $this->cartService->getUserCarts($this->user);
        $defaultCart = $userCarts->firstWhere('instance', $this->getDefaultCartName())?->content ?? [];
        $wishlistCart = $userCarts->firstWhere('instance', $this->getWishlistCartName())?->content ?? [];

        $cart = [
            $this->getDefaultCartName() => $this->validate($defaultCart),
            $this->getWishlistCartName() => $this->validate($wishlistCart)
        ];

        return collect($cart);
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        $this->checkUser();

        return $this->cartService->deleteCart($this->currentInstance(), $this->user);
    }

    /**
     * @return string
     */
    public function currentInstance(): string
    {
        return $this->instance;
    }

    /**
     * @param $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
