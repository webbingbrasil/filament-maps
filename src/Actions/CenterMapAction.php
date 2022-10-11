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

    public static function getDefaultName(): ?string
    {
        return 'centerMap';
    }

    public function centerTo(array $location, int $zoom): self
    {
        $this->centerTo = $location;
        $this->zoom = $zoom;

        return $this;
    }

    protected function setUp(): void
    {
        $this->label(__('Center map'));
        $this->icon(Blade::render('<x-filamentmapsicon-o-arrows-pointing-in class="p-1" />'));
        $this->action(function () {
            $zoom = $this->zoom;
            $latlng = json_encode($this->centerTo);

            return <<<JS
                () => {
                    this.map.setView($latlng, $zoom);
                }
            JS;
        });
    }
}
