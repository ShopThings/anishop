<?php

namespace App\Http\Middleware;

use App\Exceptions\InMaintenanceModeException;
use Closure;
use ErrorException;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;
use Illuminate\Http\Request;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/settings',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ErrorException
     * @throws InMaintenanceModeException
     */
    public function handle($request, Closure $next)
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        if ($this->app->maintenanceMode()->active()) {
            try {
                $data = $this->app->maintenanceMode()->data();
            } catch (ErrorException $exception) {
                if (!$this->app->maintenanceMode()->active()) {
                    return $next($request);
                }

                throw $exception;
            }

            if (isset($data['secret']) && $request->input('maintenance_secret') === $data['secret']) {
                return $next($request);
            }

            throw new InMaintenanceModeException();
        }

        return $next($request);
    }
}
