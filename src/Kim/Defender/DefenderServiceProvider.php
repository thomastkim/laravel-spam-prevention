<?php

namespace Kim\Defender;

use Illuminate\Support\ServiceProvider;

use Kim\Defender\Validation\DefenderValidator;

class DefenderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->validator->resolver(
            function($translator, $data, $rules, $messages) {
                return new DefenderValidator($translator, $data, $rules, $messages);
            }
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDefenderSession();
        $this->registerDefenderHtmlGenerator();
        $this->app->singleton('Defender', function($app) {
            return $this->app->make('Kim\Defender\Defender');
        });
    }

    /**
     * Register a resolver for the defender session.
     *
     * @return void
     */
    private function registerDefenderSession()
    {
        $this->app->bind(
            'Kim\Defender\Contracts\DefenderSession',
            'Kim\Defender\Laravel\DefenderSession'
        );
    }

    /**
     * Register a resolver for the defender html generator.
     *
     * @return void
     */
    private function registerDefenderHtmlGenerator()
    {
        $this->app->bind(
            'Kim\Defender\Contracts\DefenderHtmlGenerator',
            'Kim\Defender\Laravel\DefenderHtmlGenerator'
        );
    }
}
