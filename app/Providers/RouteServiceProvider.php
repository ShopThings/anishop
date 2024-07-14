<?php

namespace App\Providers;

use App\Enums\Gates\RolesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * All files that has API routes, declare here
     *
     * @var array
     */
    public array $api_route_files = [
        'routes/API/base.php',
        'routes/API/admin.php',
        'routes/API/user.php',
    ];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $user = $request->user();
            $rpm = !empty($user) && $user->hasRole(RolesEnum::DEVELOPER->value)
                ? 300
                : 100;

            return Limit::perMinute($rpm)->by($user?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'type' => ResponseTypesEnum::ERROR->value,
                        'message' => 'تعداد دفعات درخواست بیشتر از حد مجاز است. لطفا چند لحظه صبر کنید و سپس تلاش کنید.',
                    ], ResponseCodes::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });
        RateLimiter::for('panel', function (Request $request) {
            return Limit::perMinute(500)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            foreach ($this->api_route_files as $api_file) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group(base_path($api_file));
            }

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
