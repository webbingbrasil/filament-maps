<?php

namespace Webbingbrasil\FilamentMaps;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use BladeUI\Icons\Factory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMapsProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-maps')
            ->hasViews();
    }

    public function registeringPackage(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('filamentmapsicons', array_merge(
                ['path' => __DIR__.'/../resources/svg'],
                ['prefix' => 'filamentmapsicon']
            ));
        });
    }

    public function packageBooted(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): View => view('filament-maps::styles'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_BEFORE,
            fn (): View => view('filament-maps::scripts'),
        );
    }
}
