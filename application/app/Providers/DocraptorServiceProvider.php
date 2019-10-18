<?php

declare(strict_types=1);

namespace App\Providers;

use DocRaptor\Configuration;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository;

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
