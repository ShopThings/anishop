<?php

namespace App\Http\Requests\Filters;

use App\Support\Filter;
use Illuminate\Http\Request;

class OrderFilter extends Filter
{
    /**
     * @var array|string|null
     */
    protected array|string|null $badgeCode = null;

    /**
     * @return array|string|null
     */
    public function getBadgeCode(): array|string|null
    {
        return $this->badgeCode;
    }

    /**
     * @param array|string|null $code
     * @return $this
     */
    public function setBadgeCode(array|string|null $code): static
    {
        $this->badgeCode = $code;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->badgeCode = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $code = $request->input('with_badge_code');
        if (is_array($code) || is_string($code)) {
            $this->setBadgeCode($code);
        }
    }
}
