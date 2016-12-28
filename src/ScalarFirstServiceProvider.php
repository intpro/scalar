<?php

namespace Interpro\Scalar;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ScalarFirstServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {
        Log::info('Загрузка ScalarFirstServiceProvider');

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * @return void
     */
    public function register()
    {
        Log::info('Регистрация ScalarFirstServiceProvider');

        $forecastList = $this->app->make('Interpro\Core\Contracts\Taxonomy\TypesForecastList');

        $forecastList->registerCTypeName('string');
        $forecastList->registerCTypeName('text');
        $forecastList->registerCTypeName('int');
        $forecastList->registerCTypeName('float');
        $forecastList->registerCTypeName('bool');
    }

}
