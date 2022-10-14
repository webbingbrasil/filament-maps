<?php

namespace Webbingbrasil\FilamentMaps\Actions;

use Closure;
use Filament\Support\Actions\Concerns;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Blade;

class ZoomAction extends Action
{
    protected string $view = 'filament-maps::zoom-action';

    protected int $zoom = 1;
    protected string $type = '+';

    protected function setUp(): void
    {
        $this
            ->label(__('Zoom'))
            ->icon('heroicon-o-plus');
    }

    public function increment(): static
    {
        return $this->callback(function () {
            return <<<JS
                () => { map.setZoom(map.getZoom() + $this->zoom); }
            JS;
        });
    }

    public function decrement(): static
    {
        return $this->callback(function () {
            return <<<JS
                () => { map.setZoom(map.getZoom() - $this->zoom); }
            JS;
        });
    }

    public function zoom(int $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public static function getDefaultName(): ?string
    {
        return 'zoomIn';
    }
}
