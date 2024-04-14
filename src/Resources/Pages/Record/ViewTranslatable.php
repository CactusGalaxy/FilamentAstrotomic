<?php

declare(strict_types=1);

namespace CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record;

use Astrotomic\Translatable\Contracts\Translatable;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\HasLocales;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin ViewRecord
 */
trait ViewTranslatable
{
    use HasLocales;

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        /** @var Model|Translatable $record */
        $record = $this->getRecord();

        $recordAttributes = $record->attributesToArray();

        $recordAttributes = $this->mutateTranslatableData($record, $recordAttributes);

        $recordAttributes = $this->mutateFormDataBeforeFill($recordAttributes);

        $this->form->fill($recordAttributes);

        $this->callHook('afterFill');
    }
}
