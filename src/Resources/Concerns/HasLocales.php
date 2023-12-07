<?php

namespace CactusGalaxy\FilamentAstrotomic\Resources\Concerns;

use CactusGalaxy\FilamentAstrotomic\FilamentAstrotomicTranslatableContentDriver;
use CactusGalaxy\FilamentAstrotomic\FilamentAstrotomicTranslatablePlugin;
use Filament\Support\Contracts\TranslatableContentDriver;

trait HasLocales
{
    use MutateTranslatableData;

    /**
     * @return class-string<TranslatableContentDriver> | null
     */
    public function getFilamentTranslatableContentDriver(): ?string
    {
        return FilamentAstrotomicTranslatableContentDriver::class;
    }

    public static function getTranslatableLocales(): array
    {
        /** @var FilamentAstrotomicTranslatablePlugin $plugin */
        $plugin = filament('filament-astrotomic-translatable');

        return $plugin->allLocales();
    }
}
