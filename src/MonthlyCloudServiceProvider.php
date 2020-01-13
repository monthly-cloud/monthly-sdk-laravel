<?php

namespace MonthlyCloud\Laravel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use MonthlyCloud\Laravel\Providers\MonthlyCloudUserProvider;
use MonthlyCloud\Sdk\Builder;
use MonthlyCloud\Sdk\StorageBuilder;

class MonthlyCloudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::provider('cloud', function ($app, array $config) {
            return new MonthlyCloudUserProvider($app['hash'], $config['model']);
        });

        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Builder::class, function ($app) {
            $builder = new Builder(
                config('monthlycloud.access_token'),
                config('monthlycloud.api_url')
            );
            $builder->setCache(new \MonthlyCloud\Laravel\Cache(config('monthlycloud.cache_store')));
            $builder->cacheTtl(config('monthlycloud.cache_ttl', 60));

            return $builder;
        });
        $this->app->singleton(StorageBuilder::class, function ($app) {
            $builder = new StorageBuilder(
                config('monthlycloud.storage_url')
            );
            if ($listingId = config('monthlycloud.listing_id')) {
                $builder->listing($listingId);
            }
            if ($websiteId = config('monthlycloud.website_id')) {
                $builder->website($websiteId);
            }
            $builder->locale($this->app->getLocale());
            $builder->setCache(new \MonthlyCloud\Laravel\Cache(config('monthlycloud.cache_store')));
            $builder->cacheTtl(config('monthlycloud.cache_ttl', 60));

            return $builder;
        });
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/monthlycloud.php' => config_path('monthlycloud.php'),
            ]);
        }
    }
}
