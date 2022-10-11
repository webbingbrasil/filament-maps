<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Webbingbrasil\FilamentMaps\Marker;

trait HasMarkers
{
    protected array $markers = [];

    public function markers(array $mapMarkers): self
    {
        $this->markers = $mapMarkers;

        return $this;
    }

    public function addMarker(Marker $marker): self
    {
        $this->markers[] = $marker;

        return $this;
    }

    public function getMarkers(): array
    {
        return $this->markers;
    }

}
