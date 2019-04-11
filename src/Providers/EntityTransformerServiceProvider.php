<?php

namespace EliPett\EntityTransformer\Providers;

use EliPett\EntityTransformer\Services\Transform;
use Illuminate\Support\ServiceProvider;

class EntityTransformerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind('transform', Transform::class);
    }
}