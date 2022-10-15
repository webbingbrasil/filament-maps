<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Webbingbrasil\FilamentMaps\Polyline;

trait HasPolylines
{
    public array $polylines = [];

    public function configurePolylines(): self
    {
        $this->polylines = $this->preparePolylines($this->getPolylines());

        return $this;
    }

    protected function preparePolylines(array $data): array
    {
        return collect($data)
            ->map(function (array | Polyline $item) {
                if ($item instanceof Polyline) {
                    return $item->toArray();
                }

                return $item;
            })
            ->toArray();
    }

    public function addPolyline(Polyline $polyline): self
    {
        $this->polylines[] = $polyline->toArray();

        return $this;
    }

    public function removePolyline(string $id): self
    {
        $this->polylines = collect($this->polylines)
            ->filter(fn($polyline) => $polyline['id'] !== $id)
            ->toArray();

        return $this;
    }

    public function updatePolyline(Polyline $polyline): self
    {
        $this->polylines = collect($this->polylines)
            ->map(fn($m) => $m['id'] === $polyline->getName() ? $polyline->toArray() : $m)
            ->toArray();

        return $this;
    }

    public function getPolylines(): array
    {
        return [];
    }

    public function mapPolylines(array $polylines): self
    {
        $this->polylines = $this->preparePolylines($polylines);

        return $this;
    }
}
