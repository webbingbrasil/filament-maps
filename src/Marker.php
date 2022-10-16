<?php

namespace Webbingbrasil\FilamentMaps;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;
use Webbingbrasil\FilamentMaps\Concerns\HasPopup;
use Webbingbrasil\FilamentMaps\Concerns\HasTooltip;

class Marker implements Arrayable
{
    use EvaluatesClosures;
    use HasPopup;
    use HasTooltip;

    protected string $name;

    protected float | Closure $lat;

    protected float | Closure $lng;

    protected string | Closure | null $callback = '';

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


    public function callback(string | Closure | null  $callback): static
    {
        $this->callback = $callback;

        return $this;
    }

    public function getCallback(): ?string
    {
        return $this->evaluate($this->callback);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getName(),
            'lat' => $this->getLat(),
            'lng' => $this->getLng(),
            'popup' => $this->getPopup(),
            'tooltip'=> $this->getTooltip(),
            'callback' => $this->getCallback(),
        ];
    }
}
