<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Closure;

trait HasMapMarkers
{
    protected array | Closure $mapMarkers = [];

    public function mapMarkers(array | Closure $mapMarkers): self
    {
        $this->mapMarkers = $mapMarkers;

        return $this;
    }

    public function getMapMarkers(): array
    {
        return $this->evaluate($this->mapMarkers);
    }

}
