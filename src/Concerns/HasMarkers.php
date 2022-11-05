<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\MarkerCluster;

trait HasMarkers
{
    public array $markers = [];

    public function configureMarkers(): self
    {
        $this->markers = $this->prepareMarkers($this->getMarkers());

        return $this;
    }

    protected function prepareMarkers(array $data): array
    {
        return collect($data)
            ->map(function (array | Marker | MarkerCluster $item) {
                if ($item instanceof Marker || $item instanceof MarkerCluster) {
                    return $item->toArray();
                }

                return $item;
            })
            ->toArray();
    }

    public function addMarker(Marker $marker): self
    {
        $this->markers[] = $marker->toArray();

        return $this;
    }

    public function removeMarker(string $id): self
    {
        $this->markers = collect($this->markers)
            ->filter(fn($marker) => $marker['id'] !== $id)
            ->toArray();

        return $this;
    }

    public function updateMarker(Marker $marker): self
    {
        $this->markers = collect($this->markers)
            ->map(fn($m) => $m['id'] === $marker->getName() ? $marker->toArray() : $m)
            ->toArray();

        return $this;
    }

    public function getMarkers(): array
    {
        return [];
    }

    public function mapMarkers(array $markers): self
    {
        $this->markers = $this->prepareMarkers($markers);

        return $this;
    }
}
