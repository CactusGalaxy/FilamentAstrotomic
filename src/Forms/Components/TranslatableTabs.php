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
    protected FilamentAstrotomicTranslatablePlugin $plugin;

    /**
     * Callback to generate the name of the tab.
     */
    protected ?Closure $nameGenerator = null;

    /**
     * Available locales of the application.
     */
    protected array $availableLocales;

    /**
     * Main locale of the application.
     */
    protected string $mainLocale;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var FilamentAstrotomicTranslatablePlugin $plugin */
        $this->plugin = $plugin = filament('filament-astrotomic-translatable');

        $this->availableLocales = $plugin->allLocales();
        $this->mainLocale = $plugin->getMailLocale();
    }

    /**
     * Set the callback to generate the name of the tab.
     *
     * @param  Closure(string $name, string $locale):string|null  $callback
     */
    public function makeNameUsing(?Closure $callback): static
    {
        return $this->tap(fn () => $this->nameGenerator = $callback);
    }

    /**
     * Set the name of the tab using plain syntax `{$name}:{$locale}`.
     */
    public function makeNameUsingPlainSyntax(): static
    {
        return $this->makeNameUsing(fn (string $name, string $locale) => "{$name}:{$locale}");
    }

    /**
     * Generates the localised tabs with given schema for all available localles
     *
     * @param  callable(TranslatableTab):(array<Component>|Closure)  $tabSchema
     */
    public function localeTabSchema(callable $tabSchema): self
    {
        $localeTabs = collect($this->availableLocales)
            ->map(function (string $locale) use ($tabSchema) {
                $tab = Tab::make($locale)
                    ->label($this->plugin->getLocaleLabel($locale));

                $translatableTab = new TranslatableTab($tab, $locale, $this->mainLocale);

                $translatableTab->makeNameUsing($this->nameGenerator);

                $schema = $tabSchema($translatableTab);

                return $tab->schema($schema);
            })
            ->all();

        return $this->tabs($localeTabs);
    }
}
