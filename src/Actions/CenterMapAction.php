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

    protected function setUp(): void
    {
        $this->label(__('Center map'));
        $this->icon('filamentmapsicon-o-arrows-pointing-in');
        $this->callback(function () {
            if ($this->centerOnUserPosition) {
                return $this->getCenterOnUserPositionAction();
            }

            return $this->getCenterToAction();
        });
    }

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

    public function centerOnUserPosition(bool $condition = true, string $label = 'you are here'): self
    {
        $this->centerOnUserPosition = $condition;
        $this->userPositionLabel = $label;
        if ($condition) {
            $this
                ->name('userPosition')
                ->label(__('Center on my position'))
                ->icon('filamentmapsicon-o-map-pin');
        }

        return $this;
    }

    protected function getCenterToAction(): string
    {
        $zoom = $this->zoom;
        $latlng = json_encode($this->centerTo);

        return <<<JS
            map.setView($latlng, $zoom)
        JS;
    }

    protected function getCenterOnUserPositionAction(): string
    {
        return <<<JS
            removeMarker('userPosition');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    if (position.coords.latitude && position.coords.longitude) {
                        addMarker(
                            'userPosition',
                            position.coords.latitude,
                            position.coords.longitude,
                            '{$this->userPositionLabel}'
                        );
                        map.setView([position.coords.latitude, position.coords.longitude], $this->zoom);
                    }
                });
            }
        JS;
    }

}
