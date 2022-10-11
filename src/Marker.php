<?php

namespace Webbingbrasil\FilamentMaps;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;

class Marker
{
    use EvaluatesClosures;

    protected string $name;

    protected float | Closure $lat;

    protected float | Closure $lng;

    protected string | Closure | null $popup = null;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(?string $name = null): static
    {
        $markerClass = static::class;

        if (blank($name)) {
            throw new Exception("Action of class [$markerClass] must have a unique name, passed to the [make()] method.");
        }

        return app($markerClass, ['name' => $name]);
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function lat(float| Closure $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLat(): float
    {
        return $this->evaluate($this->lat);
    }

    public function lng(float| Closure $lng): static
    {
        $this->lng = $lng;

        return $this;
    }

    public function getLng(): float
    {
        return $this->evaluate($this->lng);
    }

    public function popup(string| Closure | null $popup): static
    {
        $this->popup = $popup;

        return $this;
    }

    public function getPopup(): ?string
    {
        return $this->evaluate($this->popup);
    }
}
