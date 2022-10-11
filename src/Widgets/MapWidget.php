<?php

namespace Webbingbrasil\FilamentMaps\Widgets;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Support\Concerns\HasExtraAttributes;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;
use Webbingbrasil\FilamentMaps\Concerns\HasMapActions;
use Webbingbrasil\FilamentMaps\Concerns\HasMapMarkers;
use Webbingbrasil\FilamentMaps\Concerns\HasMapOptions;
use Webbingbrasil\FilamentMaps\Concerns\HasTileLayer;

class MapWidget extends Widget
{
    use HasExtraAttributes;
    use HasExtraAlpineAttributes;
    use EvaluatesClosures;
    use HasTileLayer;
    use HasMapOptions;
    use HasMapActions;
    use HasMapMarkers;

    protected static string $view = 'filament-maps::widgets.map';

    protected string $height = '400px';

    protected ?string $heading = null;

    protected Closure | string | Htmlable | null $footer = null;

    protected Closure | bool $hasBorder = true;

    protected Closure | bool $rounded = true;

    public function height(string | Closure $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight(): string
    {
        return $this->evaluate($this->height);
    }

    public function heading(string | Closure $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): string | Htmlable | null
    {
        return $this->evaluate($this->heading);
    }

    public function footer(string | Closure | Htmlable | null $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    public function getFooter(): string | Htmlable | null
    {
        return $this->evaluate($this->footer);
    }

    public function hasBorder(bool | Closure $noBorder = true): self
    {
        $this->hasBorder = $noBorder;

        return $this;
    }

    public function getHasBorder(): bool
    {
        return $this->evaluate($this->hasBorder);
    }

    public function rounded(bool | Closure $rounded = true): self
    {
        $this->rounded = $rounded;

        return $this;
    }

    public function getRounded(): bool
    {
        return $this->evaluate($this->rounded);
    }
}
