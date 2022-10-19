<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

trait HasMapOptions
{
    public array $mapOptions = ['center' => [0, 0], 'zoom' => 2];

    public ?array $centerTo = null;

    public function centerTo(array $location, int $zoom): self
    {
        $this->centerTo = [
            'location' => $location,
            'zoom' => $zoom,
        ];

        return $this;
    }

    public function mapOptions(array $mapOptions): self
    {
        $this->mapOptions = $mapOptions;

        return $this;
    }

    public function getMapOptions(): array
    {
        return $this->mapOptions;
    }

}
