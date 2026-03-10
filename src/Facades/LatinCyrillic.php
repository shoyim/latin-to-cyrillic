<?php

namespace Shoyim\LatinToCyrillic\Facades;

use Illuminate\Support\Facades\Facade;

class LatinCyrillic extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'latin-cyrillic';
    }
}