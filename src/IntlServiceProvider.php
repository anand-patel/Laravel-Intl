<?php namespace Propaganistas\LaravelIntl;

use CommerceGuys\Intl\Country\CountryRepository;
use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\Language\LanguageRepository;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\DateServiceProvider;
use Propaganistas\LaravelIntl\Facades\Carbon;

class IntlServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCountryRepository();
        $this->registerCurrencyRepository();
        $this->registerLanguageRepository();
        $this->registerNumberRepository();

        $this->registerDateHandler();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the country repository.
     *
     * @return void
     */
    protected function registerCountryRepository()
    {
        $this->app->bind(CountryRepository::class, function ($app) {
            $repository = new CountryRepository;
            $repository->setDefaultLocale($app['config']['app.locale']);
            $repository->setFallbackLocale($app['config']['app.fallback_locale']);

            return $repository;
        });

        $this->app->alias(Country::class, 'intl.country');
    }

    /**
     * Register the currency repository.
     *
     * @return void
     */
    protected function registerCurrencyRepository()
    {
        $this->app->bind(CurrencyRepository::class, function ($app) {
            $repository = new CurrencyRepository;
            $repository->setDefaultLocale($app['config']['app.locale']);
            $repository->setFallbackLocale($app['config']['app.fallback_locale']);

            return $repository;
        });

        $this->app->alias(Currency::class, 'intl.currency');
    }

    /**
     * Register the language repository.
     *
     * @return void
     */
    protected function registerLanguageRepository()
    {
        $this->app->bind(LanguageRepository::class, function ($app) {
            $repository = new LanguageRepository;
            $repository->setDefaultLocale($app['config']['app.locale']);
            $repository->setFallbackLocale($app['config']['app.fallback_locale']);

            return $repository;
        });

        $this->app->alias(Language::class, 'intl.language');
    }

    /**
     * Register the number repository.
     *
     * @return void
     */
    protected function registerNumberRepository()
    {
        $this->app->bind(NumberFormatRepository::class, function ($app) {
            $repository = new NumberFormatRepository;
            $repository->setDefaultLocale($app['config']['app.locale']);
            $repository->setFallbackLocale($app['config']['app.fallback_locale']);

            return $repository;
        });

        $this->app->alias(Number::class, 'intl.number');
    }

    /**
     * Register the date handler.
     *
     * @return void
     */
    protected function registerDateHandler()
    {
        $this->app->register(DateServiceProvider::class);

        $this->app->booted(function ($app) {
            \Jenssegers\Date\Date::setFallbackLocale($app['config']['app.fallback_locale']);
        });

        $this->app->bind('intl.date', function ($app) {
           return new Date;
        });

        $this->app->bind(\Carbon\Carbon::class, function ($app) {
            return $app->make('intl.date');
        });
    }
}