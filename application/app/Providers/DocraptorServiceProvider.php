<?php

namespace App\Providers;

use DocRaptor\Configuration;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

class DocraptorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Configuration::class, function () {
            /** @var Repository $config */
            $config = $this->app['config'];

            $configuration = Configuration::getDefaultConfiguration();
            $configuration->setUsername($config->get('services.docraptor.api_key'));

            return $configuration;
        });
    }
}
