<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Closure;

trait HasTileLayer
{
    protected string | Closure $tileLayerUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

    protected array | Closure $tileLayerOptions = [
        'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    ];

    public function tileLayer(string | Closure $url, array | Closure $options = []): static
    {
        return $this->tileLayerUrl($url)->tileLayerOptions($options);
    }

    public function getTileLayerUrl(): string
    {
        return $this->evaluate($this->tileLayerUrl);
    }

    public function tileLayerUrl(string | Closure $tileLayerUrl): self
    {
        $this->tileLayerUrl = $tileLayerUrl;

        return $this;
    }

    public function getTileLayerOptions(): array
    {
        return $this->evaluate($this->tileLayerOptions);
    }

    public function tileLayerOptions(array | Closure $tileLayerOptions): self
    {
        $this->tileLayerOptions = $tileLayerOptions;

        return $this;
    }
}
