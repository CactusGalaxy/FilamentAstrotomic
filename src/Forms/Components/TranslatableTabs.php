<?php

namespace CactusGalaxy\FilamentAstrotomic\Forms\Components;

use CactusGalaxy\FilamentAstrotomic\FilamentAstrotomicTranslatablePlugin;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Closure;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

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

    /**
     * Holds the tabs that will be prepended to the tabs.
     */
    protected array $prependTabs = [];

    /**
     * Holds the localised tabs.
     */
    protected array $localeTabs = [];

    /**
     * Holds the tabs that will be appended to the tabs.
     */
    protected array $appendTabs = [];

    protected function setUp(): void
    {
        parent::setUp();

        /** @var FilamentAstrotomicTranslatablePlugin $plugin */
        $this->plugin = $plugin = filament('filament-astrotomic-translatable');

        $this->availableLocales = $plugin->allLocales();
        $this->mainLocale = $plugin->getMailLocale();

        /**
         * Merge all tabs in the correct order.
         */
        $this->tabs(fn () => [
            ...$this->prependTabs,
            ...$this->localeTabs,
            ...$this->appendTabs,
        ]);
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
     * Generates the localised tabs with given schema for all available locales
     *
     * @param  callable(TranslatableTab):(array<Component>|Closure)  $tabSchema
     */
    public function localeTabSchema(callable $tabSchema): self
    {
        $this->localeTabs = collect($this->availableLocales)
            ->map(function (string $locale) use ($tabSchema) {
                $tab = Tab::make($locale)
                    ->label($this->plugin->getLocaleLabel($locale));

                $translatableTab = new TranslatableTab($tab, $locale, $this->mainLocale);

                $translatableTab->makeNameUsing($this->nameGenerator);

                $schema = $this->evaluate(
                    $tabSchema,
                    namedInjections: ['translatableTab' => $translatableTab],
                    typedInjections: [TranslatableTab::class => $translatableTab],
                );

                return $tab->schema($schema);
            })
            ->all();

        return $this;
    }

    /**
     * Prepends tabs before localised tabs.
     *
     * @param  array|callable():(array)  $tabs
     * @return $this
     */
    public function prependTabs(array|callable $tabs = []): self
    {
        $this->prependTabs = $this->evaluate($tabs);

        return $this;
    }

    /**
     * Appends tabs after localised tabs.
     *
     * @param  array|callable():(array)  $tabs
     * @return $this
     */
    public function appendTabs(array|callable $tabs = []): self
    {
        $this->appendTabs = $this->evaluate($tabs);

        return $this;
    }
}
