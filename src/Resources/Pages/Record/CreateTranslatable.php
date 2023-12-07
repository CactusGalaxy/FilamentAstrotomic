<?php

declare(strict_types=1);

namespace CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record;

use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\HasLocales;
use Filament\Resources\Pages\CreateRecord;

/**
 * @mixin CreateRecord
 */
trait CreateTranslatable
{
    use HasLocales;

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $this->form->fill();

        $this->callHook('afterFill');
    }
}
