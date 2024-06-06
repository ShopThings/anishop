<?php

namespace App\Providers;

use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
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
        /*
         * blade directives
         */
        Blade::if('notproduction', function () {
            return !app()->isProduction();
        });

        //

        Collection::macro('pluckMultiple', function ($keys) {
            return $this->map(function ($item) use ($keys) {
                return collect($item)->only($keys)->all();
            });
        });

        /*
         * where like expression
         */
        Builder::macro('whereLike', function (string|array $columns, string $search, $replacement = '%{value}%', string $operator = 'and') {
            $this->where(function (Builder $query) use ($columns, $search, $operator, $replacement) {
                $columns = Arr::wrap($columns);
                foreach ($columns as $column) {
                    $query->where($column, 'like', str_replace('{value}', $search, $replacement), mb_strtolower($operator));
                }
            });

            return $this;
        });
        Builder::macro('orWhereLike', function (string|array $columns, string $search, $replacement = '%{value}%') {
            $this->orWhere(function (Builder $query) use ($columns, $search, $replacement) {
                $query->whereLike($columns, $search, 'or', $replacement);
            });

            return $this;
        });
        Builder::macro('whereNotLike', function (string|array $columns, string $search, $replacement = '%{value}%', string $operator = 'and') {
            $this->where(function (Builder $query) use ($columns, $search, $operator, $replacement) {
                $columns = Arr::wrap($columns);
                foreach ($columns as $column) {
                    $query->where($column, 'not like', str_replace('{value}', $search, $replacement), mb_strtolower($operator));
                }
            });

            return $this;
        });
        Builder::macro('orWhereNotLike', function (string|array $columns, string $search, $replacement = '%{value}%') {
            $this->orWhere(function (Builder $query) use ($columns, $search, $replacement) {
                $query->whereNotLike($columns, $search, 'or', $replacement);
            });

            return $this;
        });

        /*
         * where regexp expression
         */
        Builder::macro('whereRegex', function (string|array $columns, string $search, string $operator = 'and') {
            $this->where(function (Builder $query) use ($columns, $search, $operator) {
                $columns = Arr::wrap($columns);
                foreach ($columns as $column) {
                    $query->where($column, 'REGEXP', $search, mb_strtolower($operator));
                }
            });

            return $this;
        });
        Builder::macro('orWhereRegex', function (string|array $columns, string $search) {
            $this->orWhere(function (Builder $query) use ($columns, $search) {
                $query->whereRegex($columns, $search, 'or');
            });

            return $this;
        });
    }
}
