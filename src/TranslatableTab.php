<?php

declare(strict_types=1);

namespace CactusGalaxy\FilamentAstrotomic;

use Filament\Forms\Components\Tabs\Tab;

class TranslatableTab
{
    public function __construct(
        protected Tab $tab,
        protected string $locale,
        protected string $mailLocale,
    ) {
    }

    public function getTab(): Tab
    {
        return $this->tab;
    }

    public function isMainLocale(): bool
    {
        return $this->locale === $this->mailLocale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getMailLocale(): string
    {
        return $this->mailLocale;
    }

    public function makeName(string $name): string
    {
        return "{$this->locale}.{$name}";
    }
}
