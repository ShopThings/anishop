<?php

namespace App\Support\Cart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use UnexpectedValueException;

class CartItem implements Arrayable
{
    /**
     * @var Model
     */
    private Model $model;

    /**
     * @var int
     */
    private int $quantity;

    public function __construct(Model $model, int $quantity)
    {
        if (!$model instanceof BuyableInterface) {
            throw new InvalidArgumentException('محصول انتخاب شده باید از نوع قابل خرید باشد.');
        }
        $this->model = $model;

        if ($quantity <= 0) {
            $quantity = 1;
        }
        $this->quantity = $quantity;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $arr = $this->toArray();
        if (array_key_exists($name, $arr)) {
            return $arr[$name];
        }

        throw new UnexpectedValueException('[' . $name . '] not found on class');
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $info = [
            'id' => $this->model->getBuyableIdentifier(),
            'name' => $this->model->getBuyableDescription(),
            'price' => $this->model->getBuyablePrice(),
        ];

        return [
                'qty' => $this->quantity,
            ] + $info + [
                'product_id' => $this->model->product_id,
                'code' => $this->model->code,
                'actual_price' => $this->model->price,
                'tax_rate' => $this->model->tax_rate,
                'color_name' => $this->model->color_name,
                'color_hex' => $this->model->color_hex,
                'size' => $this->model->size,
                'guarantee' => $this->model->guarantee,
                'weight' => $this->model->weight,
                'stock_count' => $this->model->stock_count,
                'max_cart_count' => $this->model->max_cart_count,
                'is_special' => $this->model->is_special,
                'has_separate_shipment' => $this->model->has_separate_shipment,
            ];
    }
}
