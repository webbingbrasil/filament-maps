<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

trait HasTileLayer
{
    protected string $tileLayerMode = 'default';

    protected string | array $tileLayerUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

    protected array $tileLayerOptions = [
        'attribution' => '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
    ];

    public function tileLayerMode(string $tileLayerMode): static
    {
        $this->tileLayerMode = $tileLayerMode;

        return $this;
    }

    public function tileLayerUrl(string | array $tileLayerUrl): static
    {
        $this->tileLayerUrl = $tileLayerUrl;

        return $this;
    }

    public function tileLayerOptions(array $tileLayerOptions): static
    {
        $this->tileLayerOptions = $tileLayerOptions;

        return $this;
    }

    public function getTileLayerUrl(): string | array
    {
        if(is_array($this->tileLayerUrl)) {
            return $this->tileLayerUrl;
        }

        return [$this->tileLayerMode => $this->tileLayerUrl];
    }

    public function getTileLayerOptions(): array
    {
        return $this->tileLayerOptions;
    }

    public function getTileLayerMode(): string
    {
        $titleLayerModes = array_keys($this->getTileLayerUrl());

        if (in_array($this->tileLayerMode, $titleLayerModes)) {
            return $this->tileLayerMode;
        }

        return $titleLayerModes[0];
    }
}
