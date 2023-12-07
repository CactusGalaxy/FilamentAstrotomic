<?php

namespace CactusGalaxy\FilamentAstrotomic;

use Astrotomic\Translatable\Locales;
use Closure;
use Exception;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Str;
use Throwable;

class FilamentAstrotomicTranslatablePlugin implements Plugin
{
    protected ?Closure $getLocaleLabelUsing = null;

    final public function __construct()
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-astrotomic-translatable';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * @return array<string>
     *
     * @throws Throwable
     */
    public function allLocales(): array
    {
        return tap(app(Locales::class)->all(), function (array $locales) {
            foreach ($locales as $locale) {
                throw_if(! is_string($locale), new Exception('Sorry, but the locales must be strings.'));
            }
        });
    }

    public function getMailLocale(): string
    {
        return app(Locales::class)->current();
    }

    public function getLocaleLabelUsing(?Closure $callback): void
    {
        $this->getLocaleLabelUsing = $callback;
    }

    public function getLocaleLabel(string $locale, ?string $displayLocale = null): ?string
    {
        $displayLocale ??= app()->getLocale();

        $label = null;

        if ($callback = $this->getLocaleLabelUsing) {
            $label = $callback($locale, $displayLocale);
        }

        return $label ?? Str::ucfirst(locale_get_display_name($locale, $displayLocale) ?: '');
    }
}
