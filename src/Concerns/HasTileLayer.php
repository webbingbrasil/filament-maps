<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

trait HasTileLayer
{
    protected string $tileLayerUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

    protected array $tileLayerOptions = [
        'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    ];

    public function getTileLayerUrl(): string
    {
        return $this->tileLayerUrl;
    }

    public function getTileLayerOptions(): array
    {
        return $this->tileLayerOptions;
    }
}
