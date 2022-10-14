<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

trait HasDarkModeTiles
{
    protected string $lightModeTileLayerUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

    protected string $darkModeTileLayerUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

    public function bootHasDarkModeTiles(): void
    {
        $this->extraAlpineAttributes([
            'x-on:dark-mode-toggled.window' => 'setTileLayer($event.detail)',
        ]);

        $this->tileLayerUrl =  [
            'light' => $this->lightModeTileLayerUrl,
            'dark' => $this->darkModeTileLayerUrl,
        ];

        $this->tileLayerOptions = [
            'light' => [
                'attribution' => '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
            ],
            'dark' => [
                'attribution' => '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
            ]
        ];

        $this->tileLayerMode = 'light';
    }
}
