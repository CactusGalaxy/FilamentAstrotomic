<?php

declare(strict_types=1);

namespace CactusGalaxy\FilamentAstrotomic;

use Closure;
use Filament\Forms\Components\Tabs\Tab;

class TranslatableTab
{
    protected ?Closure $nameGenerator = null;

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
        if ($this->nameGenerator instanceof Closure) {
            return ($this->nameGenerator)($name, $this->locale);
        }

        return "{$this->locale}.{$name}";
    }

    public function makeNameUsing(?Closure $callback = null): void
    {
        $this->nameGenerator = $callback;
    }
}
