<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Webbingbrasil\FilamentMaps\Polyline;

trait HasPolylines
{
    public array $polyLines = [];

    public function addPolyline(Polyline $polyline): self
    {
        $this->polyLines[] = $polyline->toArray();

        return $this;
    }

    public function removePolyline(string $id): self
    {
        $this->polyLines = collect($this->polyLines)
            ->filter(fn($polyline) => $polyline['id'] !== $id)
            ->toArray();

        return $this;
    }

    public function updatePolyline(Polyline $polyline): self
    {
        $this->polyLines = collect($this->polyLines)
            ->map(fn($m) => $m['id'] === $polyline->getName() ? $polyline->toArray() : $m)
            ->toArray();

        return $this;
    }
    public function getPolylines(): array
    {
        return [];
    }
}