<?php

namespace CactusGalaxy\FilamentAstrotomic;

use Astrotomic\Translatable\Contracts\Translatable;
use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use function Filament\Support\generate_search_column_expression;

class FilamentAstrotomicTranslatableContentDriver implements TranslatableContentDriver
{
    public function __construct(protected string $activeLocale)
    {
    }

    public function isAttributeTranslatable(string $model, string $attribute): bool
    {
        /** @var Model|Translatable $model */
        $model = app($model);

        if (!method_exists($model, 'isTranslationAttribute')) {
            return false;
        }

        return $model->isTranslationAttribute($attribute);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function makeRecord(string $model, array $data): Model
    {
        /** @var Model|Translatable $model */
        $record = new $model();

        $record->fill($data);

        return $record;
    }

    /**
     * @param Model|Translatable $record
     * @return Model
     */
    public function setRecordLocale(Model|Translatable $record): Model
    {
        if (method_exists($record, 'setDefaultLocale')) {
            $record->setDefaultLocale($this->activeLocale);
        }

        return $record;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateRecord(Model $record, array $data): Model
    {
        $record->fill($data);

        $record->save();

        return $record;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRecordAttributesToArray(Model $record): array
    {
        /** @var Model|Translatable $record */
        $attributes = $record->attributesToArray();

        return $this->mutateTranslatableData($record, $attributes);
    }

    /*todo - not tested yet*/
    public function applySearchConstraintToQuery(
        Builder $query,
        string $column,
        string $search,
        string $whereClause,
        ?bool $isCaseInsensitivityForced = null
    ): Builder {
        /** @var Connection $databaseConnection */
        $databaseConnection = $query->getConnection();

        $databaseConnection->getDriverName();

        return $query->{$whereClause}(
            generate_search_column_expression($column, $isCaseInsensitivityForced, $databaseConnection),
            'like',
            "%{$search}%",
        );
    }

    /**
     * @param Model|Translatable $record
     * @param array $data
     * @return array
     */
    protected static function mutateTranslatableData(Model|Translatable $record, array $data = []): array
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
