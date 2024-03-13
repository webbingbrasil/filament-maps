<?php

namespace Webbingbrasil\FilamentMaps\Widgets;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Support\Concerns\HasExtraAttributes;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;
use Webbingbrasil\FilamentMaps\Concerns;

abstract class MapWidget extends Widget implements HasForms, HasActions
{
    use HasExtraAttributes;
    use HasExtraAlpineAttributes;
    use EvaluatesClosures;
    use InteractsWithActions;
    use InteractsWithForms;
    use Concerns\InteractsWithMapActions;
    use Concerns\HasTileLayer;
    use Concerns\HasMapOptions;
    use Concerns\HasMarkers;
    use Concerns\HasPolylines;
    use Concerns\HasPolygones;
    use Concerns\HasRectangles;
    use Concerns\HasCircles;

    use Configurable {
        configure as protected configureWidget;
    }

    protected static string $view = 'filament-maps::widgets.map';

    protected string $height = '400px';

    protected string | Htmlable | null $heading = null;

    protected string | Htmlable | null $footer = null;

    protected bool $hasBorder = true;

    protected bool $rounded = true;

    protected bool $fullpage = false;

    public ?array $fitBounds = null;

    public function mount()
    {
        $this->configure();
    }

    public function configure(): static
    {
        return $this
            ->configureMarkers()
            ->configurePolylines()
            ->configurePolygones()
            ->configureRectangles()
            ->configureCircles();
//            ->configureWidget();
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

    public function isFullPage(): bool
    {
        return $this->fullpage;
    }

    public function fitBounds(array $bounds): self
    {
        $this->fitBounds = $bounds;

        return $this;
    }

    public function getFitBounds(): ?array
    {
        return $this->fitBounds;
    }
}
