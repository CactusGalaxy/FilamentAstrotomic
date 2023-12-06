<?php

namespace CactusGalaxy\FilamentAstrotomic\Commands;

use Illuminate\Console\Command;

class FilamentAstrotomicCommand extends Command
{
    public $signature = 'filament-astrotomic';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
