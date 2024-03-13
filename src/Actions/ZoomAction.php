<?php

namespace Webbingbrasil\FilamentMaps\Actions;

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
        return $this->alpineClickHandler(function () {
            return <<<JS
                () => { map.setZoom(map.getZoom() + $this->zoom); }
            JS;
        });
    }

    public function decrement(): static
    {
        return $this->alpineClickHandler(function () {
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
