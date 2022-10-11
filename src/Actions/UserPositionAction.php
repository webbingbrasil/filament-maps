<?php

namespace Webbingbrasil\FilamentMaps\Actions;

use Closure;
use Filament\Support\Actions\Concerns;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Blade;

class UserPositionAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return 'userPosition';
    }

    protected function setUp(): void
    {
        $this->label(__('Center on my position'));
        $this->icon(Blade::render('<x-filamentmapsicon-o-map-pin class="p-1" />'));
        $this->action(<<<JS
            () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        this.removeMarker('userPosition');
                        this.addMarker('userPosition', position.coords.latitude, position.coords.longitude, 'Você está aqui');
                        this.map.setView([position.coords.latitude, position.coords.longitude], 13);
                    });
                }
            }
        JS);
    }
}
