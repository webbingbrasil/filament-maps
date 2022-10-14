<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Closure;

trait HasMapOptions
{
    protected array $mapOptions = ['center' => [0, 0], 'zoom' => 2];

    public function mapOptions(array | Closure $mapOptions): self
    {
        $this->mapOptions = $mapOptions;

        return $this;
    }

    public function getMapOptions(): array
    {
        return $this->mapOptions;
    }

}
