<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\FileRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\FileServiceInterface;
use App\Services\Contracts\RoleServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\FileService;
use App\Services\RoleService;
use App\Services\UserService;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerBase();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootBase();
        $this->bootMacros();
    }

    /**
     * @return void
     */
    private function registerBase(): void
    {
        $this->app->bind(WhereBuilderInterface::class, WhereBuilder::class);
    }

    /**
     * @return void
     */
    private function bootBase(): void
    {
        // prerequisites
        $locale = $this->app->getLocale();
        $isProd = $this->app->isProduction();

        // set carbon locale from application global localization
        Carbon::setLocale($locale);
        Verta::setLocale($locale);
        // set current money unit considering localization
        config([
            'market.current_money_unit' => config(
                'market.money_unit.' . $locale,
                config('market.default_money_unit')
            )
        ]);

        // set default lazy loading of models
        Model::preventLazyLoading(!$isProd);

        // default password rule validation behavior
        PasswordRule::defaults(function () use ($isProd) {
            $rule = PasswordRule::min(8);

            return $isProd
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });
    }

    /**
     * @return void
     */
    public function bootMacros(): void
    {
        Collection::macro('pluckMultiple', function ($keys) {
            return $this->map(function ($item) use ($keys) {
                return collect($item)->only($keys)->all();
            });
        });

        //

        Builder::macro('whereLike', function (string|array $columns, string $search, string $operator = 'or') {
            $this->where(function (Builder $query) use ($columns, $search, $operator) {
                $columns = !is_array($columns) ? [$columns] : $columns;
                foreach ($columns as $column) {
                    if (mb_strtolower($operator) == 'and') {
                        $query->where($column, 'like', '%' . $search . '%');
                    } else {
                        $query->orWhere($column, 'like', '%' . $search . '%');
                    }
                }
            });

            return $this;
        });
        Builder::macro('orWhereLike', function (string|array $columns, string $search, string $operator = 'or') {
            $this->orWhere(function (Builder $query) use ($columns, $search, $operator) {
                $query->whereLike($columns, $search, $operator);
            });

            return $this;
        });
    }
}
