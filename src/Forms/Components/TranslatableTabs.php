<?php

namespace CactusGalaxy\FilamentAstrotomic\Forms\Components;

use CactusGalaxy\FilamentAstrotomic\FilamentAstrotomicTranslatablePlugin;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

class TranslatableTabs extends Tabs
{
    protected array $availableLocales;

    private string $mainLocale;

    private FilamentAstrotomicTranslatablePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var FilamentAstrotomicTranslatablePlugin $plugin */
        $this->plugin = $plugin = filament('filament-astrotomic-translatable');

        $this->availableLocales = $plugin->allLocales();
        $this->mainLocale = $plugin->getMailLocale();
    }

    /**
     * @param  callable(TranslatableTab):(array<Component>|Closure)  $tabSchema
     */
    public function localeTabSchema(callable $tabSchema): self
    {
        $localeTabs = collect($this->availableLocales)
            ->map(function (string $locale) use ($tabSchema) {
                $tab = Tab::make($locale)
                    ->label($this->plugin->getLocaleLabel($locale));

                $translatableTab = new TranslatableTab($tab, $locale, $this->mainLocale);

                $schema = $tabSchema($translatableTab);

                return $tab->schema($schema);
            })
            ->all();

        return $this->tabs($localeTabs);
    }
}
