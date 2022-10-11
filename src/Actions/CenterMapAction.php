<?php

namespace Webbingbrasil\FilamentMaps\Actions;

use Closure;
use Filament\Support\Actions\Concerns;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Blade;

class CenterMapAction extends Action
{
    protected array $centerTo = [0, 0];

    protected int $zoom = 4;

    protected bool $centerOnUserPosition = false;

    protected string $userPositionLabel = 'you are here';

    public static function getDefaultName(): ?string
    {
        return 'centerMap';
    }

    public function centerTo(array $location): self
    {
        $this->centerTo = $location;

        return $this;
    }

    public function zoom(int $zoom): self
    {
        $this->zoom = $zoom;

        return $this;
    }

    protected function setUp(): void
    {
        $this->label(__('Center map'));
        $this->icon(Blade::render('<x-filamentmapsicon-o-arrows-pointing-in class="p-1" />'));
        $this->action(function () {
            if ($this->centerOnUserPosition) {
                return $this->getCenterOnUserPositionAction();
            }

            return $this->getCenterToAction();
        });
    }

    public function centerOnUserPosition(bool $condition = true, string $label = 'you are here'): self
    {
        $this->centerOnUserPosition = $condition;
        $this->userPositionLabel = $label;
        if ($condition) {
            $this
                ->name('userPosition')
                ->label(__('Center on my position'))
                ->icon(Blade::render('<x-filamentmapsicon-o-map-pin class="p-1" />'));
        }

        return $this;
    }

    protected function getCenterToAction(): string
    {
        $zoom = $this->zoom;
        $latlng = json_encode($this->centerTo);

        return <<<JS
            () => {
                this.map.setView($latlng, $zoom);
            }
        JS;
    }

    protected function getCenterOnUserPositionAction(): string
    {
        return <<<JS
            () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        this.removeMarker('userPosition');
                        this.addMarker(
                            'userPosition',
                            position.coords.latitude,
                            position.coords.longitude,
                            '{$this->userPositionLabel}'
                        );
                        this.map.setView([position.coords.latitude, position.coords.longitude], $this->zoom);
                    });
                }
            }
        JS;
    }

}
