<?php

namespace CactusGalaxy\FilamentAstrotomic\Resources\Concerns;

use Filament\Resources\Resource;

/**
 * @mixin Resource
 */
trait ResourceTranslatable
{
    use HasLocales;
}
