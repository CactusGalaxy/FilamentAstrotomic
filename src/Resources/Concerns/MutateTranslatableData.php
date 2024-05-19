<?php

namespace CactusGalaxy\FilamentAstrotomic\Resources\Concerns;

use Astrotomic\Translatable\Contracts\Translatable;
use Illuminate\Database\Eloquent\Model;

trait MutateTranslatableData
{
    /**
     * @param Model|Translatable $record
     */
    public static function mutateTranslatableData(Model $record, array $data = []): array
    {
        if (!method_exists($record, 'getTranslationsArray')) {
            return $data;
        }

        foreach ($record->getTranslationsArray() as $locale => $attributes) {
            $data[$locale] = $attributes;
        }

        return $data;
    }
}
