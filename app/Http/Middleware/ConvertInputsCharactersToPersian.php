<?php

namespace App\Http\Middleware;

use App\Support\Converters\CharacterConverter;
use Closure;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;
use Illuminate\Http\Request;

class ConvertInputsCharactersToPersian extends TransformsRequest
{
    /**
     * @var array
     */
    protected static array $skipCallbacks = [];

    /**
     * Register a callback that instructs the middleware to be skipped.
     *
     * @param Closure $callback
     * @return void
     */
    public static function skipWhen(Closure $callback): void
    {
        static::$skipCallbacks[] = $callback;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        foreach (static::$skipCallbacks as $callback) {
            if ($callback($request)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function transform($key, $value): mixed
    {
        return CharacterConverter::toPersian($value);
    }
}
