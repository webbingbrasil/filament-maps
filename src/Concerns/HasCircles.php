<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Webbingbrasil\FilamentMaps\Circle;

trait HasCircles
{
    public array $circles = [];

    public function configureCircles(): self
    {
        $this->circles = $this->prepareCircles($this->getCircles());

        return $this;
    }

    protected function prepareCircles(array $data): array
    {
        return collect($data)
            ->map(function (array | Circle $item) {
                if ($item instanceof Circle) {
                    return $item->toArray();
                }

                return $item;
            })
            ->toArray();
    }

    public function addCircle(Circle $circle): self
    {
        $this->circles[] = $circle->toArray();

        return $this;
    }

    public function removeCircle(string $id): self
    {
        $this->circles = collect($this->circles)
            ->filter(fn($circle) => $circle['id'] !== $id)
            ->toArray();

        return $this;
    }

    public function updateCircle(Circle $circle): self
    {
        $this->circles = collect($this->circles)
            ->map(fn($m) => $m['id'] === $circle->getName() ? $circle->toArray() : $m)
            ->toArray();

        return $this;
    }

    public function getCircles(): array
    {
        return [];
    }

    public function mapCircles(array $circles): self
    {
        $this->circles = $this->prepareCircles($circles);

        return $this;
    }
}
