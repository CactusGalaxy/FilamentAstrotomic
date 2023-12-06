<?php

namespace CactusGalaxy\FilamentAstrotomic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CactusGalaxy\FilamentAstrotomic\FilamentAstrotomic
 */
class FilamentAstrotomic extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CactusGalaxy\FilamentAstrotomic\FilamentAstrotomic::class;
    }
}
