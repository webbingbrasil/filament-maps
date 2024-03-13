
<?php

namespace Webbingbrasil\FilamentMaps;

use Filament\Contracts\Plugin;
use Filament\Panel;
 
class FilamentMapsPlugin extends Plugin
{
    public function getId()
    {
        return 'filament-maps';
    }

    public function register(Panel $panel): void
    {
    }

    public function boot(Panel $panel): void
    {
    }
}
