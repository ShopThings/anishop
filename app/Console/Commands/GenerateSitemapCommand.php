<?php

namespace App\Console\Commands;

use App\Services\Contracts\BlogServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\StaticPageServiceInterface;
use App\Support\Filter;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemapCommand extends Command
{
    const CHUNK_SIZE = 1000;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap from routes.json of frontend for both frontend and backend';
    /**
     * A service should have followed structure:
     * <code>
     *   [
     *     service_name: string => [
     *       'name' => name of service: string,
     *       'method' => method that can call to retrieve data: string,
     *       'column' => name of column that should pass as value to create route: string,
     *       'updated_column' => name of column for frequency changed time: string (optional),
     *       'relations' => an array contains a key-value array that should map a service name to another service name with extra info (please see example): array (optional),
     *     ],
     *     ...,
     *   ]
     * </code>
     *
     * @example
     * <code>
     *  [
     *    'product': string => [
     *      'name' => ProductServiceInterface::class,
     *      'method' => 'getDataForSitemap',
     *      'column' => 'slug',
     *      'updated_column' => 'updated_at' (optional),
     *      'relations' => [
     *          [
     *              'with' => 'user' // Assume we have another service called "user",
     *              'relation' => [
     *                  'name' => 'creator' // Eloquent relation,
     *                  'column' => 'id' // Column that should check against,
     *              ],
     *          ],
     *          ...,
     *      ] (optional),
     *    ],
     *    ...,
     *  ]
     * </code>
     *
     * @var array|array[]
     */
    private array $mappedService = [
        'product' => [
            'name' => ProductServiceInterface::class,
            'method' => 'getDataForSitemap',
            'column' => 'slug',
            'updated_column' => 'updated_at',
        ],
        'blog' => [
            'name' => BlogServiceInterface::class,
            'method' => 'getDataForSitemap',
            'column' => 'slug',
            'updated_column' => 'updated_at',
        ],
        'page' => [
            'name' => StaticPageServiceInterface::class,
            'method' => 'getDataForSitemap',
            'column' => 'url',
            'updated_column' => 'updated_at',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemapDestination = config('market.sitemap_destination');
        if (empty($sitemapDestination)) {
            $this->error('No sitemap destination found! Add one or disable the command schedule from command kernel.');
            return;
        }

        try {
            // Please ensure you have "routes.json" file that comes from frontend, in "resources" and "sitemap" folder
            $routes = json_decode(file_get_contents(resource_path('sitemap/routes.json')), true);
            $routes = $this->getRefinedRoutes($routes);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            $this->info('It seems that the routes file is missing. Either create one or disable the command schedule from command kernel.');
            return;
        }

        $sitemap = Sitemap::create();

        // Iterate over all routes and create route either it is static or dynamic
        foreach ($routes as $route) {
            // This part is for when route is dynamic
            if (!empty($route['mapped_params'])) {
                $mappedParameters = $route['mapped_params'];

                // Simple store needed services
                $services = [];
                foreach ($mappedParameters as $type) {
                    if (array_key_exists($type, $this->mappedService)) {
                        if (!isset($services[$type])) {
                            $services[$type] = $this->mappedService[$type];
                        }
                    }
                }

                $parameterKeys = array_keys($route['mapped_params']);
                $this->parameterIterator(
                    $parameterKeys,
                    $services,
                    $mappedParameters,
                    $route,
                    $sitemap
                );
            } else { // For static situations, just add the route to sitemap instance
                $this->addToSitemap(
                    $sitemap,
                    $this->replaceRouteParameters($route['path']),
                    $route['changefreq'],
                    Carbon::now()->subWeek(),
                    (float)$route['priority']
                );
            }
        }

        // Create destination folders if it's not exists
        if (!file_exists($sitemapDestination)) {
            mkdir(directory: $sitemapDestination, recursive: true);
        }

        // Create sitemap and store on both frontend and backend
        $filename = 'sitemap.xml';
        $sitemap->writeToFile($sitemapDestination . '/' . $filename);
        $sitemap->writeToFile(public_path($filename));

        // Create robots text file or append sitemap url at the end
        $this->createOrModifyRobotsFile($sitemapDestination, $filename, config('market.frontend_url'));
        $this->createOrModifyRobotsFile(public_path(), $filename, config('app.url'));

        $this->info('Sitemap generated and published successfully.');
    }

    /**
     * @param array $routes
     * @return array
     */
    private function getRefinedRoutes(array $routes): array
    {
        $refinedRoutes = [];

        // refine routes and get validated structure
        foreach ($routes as $route) {
            if (isset($route['path']) && is_string($route['path'])) {
                $mappedParams = null;
                if (isset($route['mapped_params']) && is_array($route['mapped_params'])) {
                    $mappedParams = $route['mapped_params'];
                }

                $relations = null;
                if (isset($route['relations']) && is_array($route['relations'])) {
                    $relations = $route['relations'];
                }

                $refinedRoutes[] = [
                    'path' => $route['path'],
                    'mapped_params' => $mappedParams,
                    'relations' => $relations,
                    'changefreq' => $route['changefreq'] ?? Url::CHANGE_FREQUENCY_WEEKLY,
                    'priority' => (float)($route['priority'] ?? 0.9),
                ];
            }
        }

        return $refinedRoutes;
    }

    /**
     * @param array $parameters
     * @param array $services
     * @param array $mappedParameters
     * @param array $route
     * @param Sitemap $sitemap
     * @param array $values
     * @return void
     */
    private function parameterIterator(
        array   $parameters,
        array   $services,
        array   $mappedParameters,
        array   $route,
        Sitemap $sitemap,
        array   $values = []
    ): void
    {
        $parameter = array_shift($parameters);

        if (empty($parameter) || !isset($services[$mappedParameters[$parameter]])) {
            return;
        }

        $service = $services[$mappedParameters[$parameter]];

        $filter = new Filter();
        $filter->setLimit(self::CHUNK_SIZE);

        try {
            $instance = app()->get($service['name']);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            $this->error($e->getMessage());
            return;
        }

        $page = 1;
        do {
            $filter->setPage($page++);

            $relations = $this->getParameterRelations($parameter, $mappedParameters, $route, $values);

            /**
             * @var Collection $items
             */
            $items = $instance->{$service['method']}($filter, $relations);

            $items->each(function ($item) use (
                $service,
                $parameters,
                $services,
                $mappedParameters,
                $route,
                $sitemap,
                $values
            ) {
                $sendingValues = array_merge($values, [$item->{$service['column']}]);

                if (!empty($parameters)) {
                    $this->parameterIterator(
                        $parameters,
                        $services,
                        $mappedParameters,
                        $route,
                        $sitemap,
                        $sendingValues
                    );
                } else {
                    $keys = array_keys($mappedParameters);
                    $path = $this->replaceRouteParameters($route['path'], array_combine($keys, $sendingValues));

                    $this->addToSitemap(
                        $sitemap,
                        $path,
                        $route['changefreq'],
                        $item->{$service['updated_column']} ?? Carbon::now()->subDay(),
                        (float)$route['priority']
                    );
                }
            });
        } while ($items->count());
    }

    /**
     * @param string $parameter
     * @param array $mappedParameters
     * @param array $route
     * @param array $values
     * @return array
     */
    private function getParameterRelations(
        string $parameter,
        array  $mappedParameters,
        array  $route,
        array  $values
    ): array
    {
        $relations = [];

        if (!empty($route['relations']) && count($values)) {
            foreach ($route['relations'] as $relationFrom => $relationTo) {
                if (
                    $parameter == $relationFrom &&
                    isset($services[$mappedParameters[$relationFrom]]['relations']) &&
                    is_array($services[$mappedParameters[$relationFrom]]['relations'])
                ) {
                    foreach ($services[$mappedParameters[$relationFrom]]['relations'] as $rel) {
                        if (
                            is_array($rel) &&
                            isset($mappedParameters[$relationTo]) &&
                            $rel['with'] == $mappedParameters[$relationTo]
                        ) {
                            $keys = array_keys($mappedParameters);
                            $paramIdx = array_search($parameter, $keys, true);

                            if ($paramIdx !== false && is_numeric($paramIdx)) {
                                for ($i = $paramIdx - 1; $i >= 0; $i--) {
                                    $v = $values[$i];
                                    if (isset($v)) {
                                        $relations[] = [
                                            'relation' => $rel['relation']['name'],
                                            'callback' => function ($query) use ($rel, $v) {
                                                $query->where($rel['relation']['column'], $v);
                                            }
                                        ];

                                        break 2;
                                    }
                                }
                            }
                        }
                    }

                    break;
                }
            }
        }

        return $relations;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @return string
     */
    private function replaceRouteParameters(string $path, array $parameters = []): string
    {
        $parts = preg_split('#(?<!\\\\)/#', $path, -1, PREG_SPLIT_NO_EMPTY);
        $updatedParts = [];

        foreach ($parts as $index => $part) {
            $paramExited = false;

            foreach ($parameters as $name => $value) {
                if (preg_match('#^:' . $name . '(\(.*\)[?*]?)?$#', $part)) {
                    $updatedParts[] = $value;
                    $paramExited = true;

                    break;
                }
            }

            if (!$paramExited && !preg_match('#\)[*?]$#', $part)) {
                $updatedParts[] = $part;
            }
        }

        return implode('/', $updatedParts);
    }

    private function addToSitemap(
        Sitemap $sitemap,
        string  $path,
        string  $frequency,
        Carbon  $modifyDate,
        float   $priority
    ): void
    {
        $sitemap->add(
            Url::create(config('market.frontend_url') . '/' . $path)
                ->setChangeFrequency($frequency)
                ->setLastModificationDate($modifyDate)
                ->setPriority($priority)
        );
    }

    /**
     * @param string $path
     * @param string $sitemapFilename
     * @param string $url
     * @return void
     */
    private function createOrModifyRobotsFile(string $path, string $sitemapFilename, string $url): void
    {
        $filename = 'robots.txt';
        $neededLine = "\n" . 'Sitemap: ' . $url . '/' . $sitemapFilename . "\n";

        if (!file_exists($path . '/' . $filename)) {
            $neededLine = 'User-agent: *' . "\n" . 'Disallow:' . "\n" . $neededLine;
        }

        file_put_contents($path . '/' . $filename, $neededLine, FILE_APPEND);
    }
}
