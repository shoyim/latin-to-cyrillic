<?php

namespace Shoyim\LatinToCyrillic;

use Illuminate\Support\ServiceProvider;

class LatinCyrillicServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('latin-cyrillic', function () {
            return new Converter();
        });
    }
}