<?php

namespace CactusGalaxy\FilamentAstrotomic;

use CactusGalaxy\FilamentAstrotomic\Commands\FilamentAstrotomicCommand;
use CactusGalaxy\FilamentAstrotomic\Testing\TestsFilamentAstrotomic;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAstrotomicServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-astrotomic';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasViews()
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('CactusGalaxy/FilamentAstrotomic');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-astrotomic/{$file->getFilename()}"),
                ], 'filament-astrotomic-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentAstrotomic());
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentAstrotomicCommand::class,
        ];
    }
}
