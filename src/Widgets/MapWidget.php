<?php

namespace Webbingbrasil\FilamentMaps\Widgets;

use Filament\Forms\Contracts\HasForms;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Support\Concerns\HasExtraAttributes;
use Filament\Tables\Contracts\RendersFormComponentActionModal;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Webbingbrasil\FilamentMaps\Concerns\HasActions;
use Webbingbrasil\FilamentMaps\Concerns\HasMapOptions;
use Webbingbrasil\FilamentMaps\Concerns\HasTileLayer;
use Webbingbrasil\FilamentMaps\Marker;

abstract class MapWidget extends Widget implements HasForms, RendersFormComponentActionModal
{
    use HasExtraAttributes;
    use HasExtraAlpineAttributes;
    use EvaluatesClosures;
    use HasTileLayer;
    use HasActions;
    use HasMapOptions;
    use Configurable {
        configure as protected baseConfigure;
    }

    protected static string $view = 'filament-maps::widgets.map';

    protected string $height = '400px';

    protected string | Htmlable | null $heading = null;

    protected string | Htmlable | null $footer = null;

    protected bool $hasBorder = true;

    protected bool $rounded = true;

    public array $markers = [];

    public function boot()
    {
        $this->configure();
    }

    public function configure(): static
    {
        $this->markers = $this->prepareMapData($this->getMarkers());

        return $this->baseConfigure();
    }

    protected function prepareMapData(array $data): array
    {
        return collect($data)
            ->map(function (array | Arrayable $item) {
                if ($item instanceof Arrayable) {
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
        $this->markers = $this->prepareMapData($markers);

        return $this;
    }

    public function height(string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function heading(string | Htmlable | null $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): string | Htmlable | null
    {
        return $this->heading;
    }

    public function footer(string | Htmlable | null $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    public function getFooter(): string | Htmlable | null
    {
        return $this->footer;
    }

    public function hasBorder(bool $noBorder = true): self
    {
        $this->hasBorder = $noBorder;

        return $this;
    }

    public function getHasBorder(): bool
    {
        return $this->hasBorder;
    }

    public function rounded(bool $rounded = true): self
    {
        $this->rounded = $rounded;

        return $this;
    }

    public function getRounded(): bool
    {
        return $this->rounded;
    }
}
