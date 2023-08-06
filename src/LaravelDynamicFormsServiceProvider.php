<?php

namespace AluisioPires\LaravelDynamicForms;

use AluisioPires\LaravelDynamicForms\Console\InstallCommand;
use Illuminate\Support\ServiceProvider;

class LaravelDynamicFormsServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}