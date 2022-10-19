<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Webbingbrasil\FilamentMaps\Rectangle;

trait HasRectangles
{
    public array $rectangles = [];

    public function configureRectangles(): self
    {
        $this->rectangles = $this->prepareRectangles($this->getRectangles());

        return $this;
    }

    protected function prepareRectangles(array $data): array
    {
        return collect($data)
            ->map(function (array | Rectangle $item) {
                if ($item instanceof Rectangle) {
                    return $item->toArray();
                }

                return $item;
            })
            ->toArray();
    }

    public function addRectangle(Rectangle $rectangle): self
    {
        $this->rectangles[] = $rectangle->toArray();

        return $this;
    }

    public function removeRectangle(string $id): self
    {
        $this->rectangles = collect($this->rectangles)
            ->filter(fn($rectangle) => $rectangle['id'] !== $id)
            ->toArray();

        return $this;
    }

    public function updateRectangle(Rectangle $rectangle): self
    {
        $this->rectangles = collect($this->rectangles)
            ->map(fn($m) => $m['id'] === $rectangle->getName() ? $rectangle->toArray() : $m)
            ->toArray();

        return $this;
    }

    public function getRectangles(): array
    {
        return [];
    }

    public function mapRectangles(array $rectangles): self
    {
        $this->rectangles = $this->prepareRectangles($rectangles);

        return $this;
    }
}
