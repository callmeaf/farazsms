<?php

namespace Callmeaf\Farazsms;

use Illuminate\Support\ServiceProvider;

class CallmeafFarazsmsServiceProvider extends ServiceProvider
{
    private const CONFIGS_DIR = __DIR__ . '/../config';
    private const CONFIGS_KEY = 'callmeaf-farazsms';
    private const CONFIG_GROUP = 'callmeaf-farazsms-config';

    public function boot(): void
    {
        $this->registerConfig();
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(self::CONFIGS_DIR . '/callmeaf-farazsms.php',self::CONFIGS_KEY);
        $this->publishes([
            self::CONFIGS_DIR . '/callmeaf-farazsms.php' => config_path('callmeaf-farazsms.php'),
        ],self::CONFIG_GROUP);
    }
}
