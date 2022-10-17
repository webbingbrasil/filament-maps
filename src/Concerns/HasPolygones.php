<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Webbingbrasil\FilamentMaps\Polygone;

trait HasPolygones
{
    public array $polygones = [];

    public function configurePolygones(): self
    {
        $this->polygones = $this->preparePolygones($this->getPolygones());

        return $this;
    }

    protected function preparePolygones(array $data): array
    {
        return collect($data)
            ->map(function (array | Polygone $item) {
                if ($item instanceof Polygone) {
                    return $item->toArray();
                }

                return $item;
            })
            ->toArray();
    }

    public function addPolygone(Polygone $polygone): self
    {
        $this->polygones[] = $polygone->toArray();

        return $this;
    }

    public function removePolygone(string $id): self
    {
        $this->polygones = collect($this->polygones)
            ->filter(fn($polygone) => $polygone['id'] !== $id)
            ->toArray();

        return $this;
    }

    public function updatePolygone(Polygone $polygone): self
    {
        $this->polygones = collect($this->polygones)
            ->map(fn($m) => $m['id'] === $polygone->getName() ? $polygone->toArray() : $m)
            ->toArray();

        return $this;
    }

    public function getPolygones(): array
    {
        return [];
    }

    public function mapPolygones(array $polygones): self
    {
        $this->polygones = $this->preparePolygones($polygones);

        return $this;
    }
}
