<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSS
{
    /**
     * Specify inputs that doesn't need XSS prevention
     *
     * @var array $except
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'description',
        'not_accepted_description',
        'answer',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(strtolower($request->method()), ['put', 'post'])) {
            return $next($request);
        }

        $input = $request->all();

        array_walk_recursive($input, function (&$input, $key) {
            if (!in_array($key, $this->except))
                $input = strip_tags($input);
        });

        $request->merge($input);

        return $next($request);
    }
}
