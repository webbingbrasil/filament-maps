<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Closure;

trait HasMapOptions
{
    protected array | Closure $mapOptions = [];

    public function mapOptions(array | Closure $mapOptions): self
    {
        $this->mapOptions = $mapOptions;

        return $this;
    }

    public function getMapOptions(): array
    {
        return $this->evaluate($this->mapOptions);
    }

}
