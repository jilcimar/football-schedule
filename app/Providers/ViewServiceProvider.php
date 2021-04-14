<?php

namespace App\Providers;

use App\Http\View\Composers\HomeViewComposer;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerComposers();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(app()->resourcePath('views/pages'), 'pages');
    }

    public function registerComposers()
    {
        View::composer([
            'home',
        ], HomeViewComposer::class);
    }
}
