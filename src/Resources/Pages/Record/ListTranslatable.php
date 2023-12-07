<?php

namespace CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record;

use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\HasLocales;
use Filament\Resources\Pages\ListRecords;

/**
 * @mixin ListRecords
 */
trait ListTranslatable
{
    use HasLocales;
}
