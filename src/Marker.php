<?php

namespace Webbingbrasil\FilamentMaps;

use Closure;
use Exception;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;
use Webbingbrasil\FilamentMaps\Concerns\HasPopup;
use Webbingbrasil\FilamentMaps\Concerns\HasTooltip;

class Marker implements Arrayable
{
    use EvaluatesClosures;
    use HasPopup;
    use HasTooltip;

    const COLOR_BLUE = 'blue';
    const COLOR_GOLD = 'gold';
    const COLOR_RED = 'red';
    const COLOR_GREEN = 'green';
    const COLOR_ORANGE = 'orange';
    const COLOR_YELLOW = 'yellow';
    const COLOR_VIOLET = 'violet';
    const COLOR_GREY = 'grey';
    const COLOR_BLACK = 'black';

    protected string $name;

    protected float | Closure $lat;

    protected float | Closure $lng;

    protected string | Closure | null $callback = '';

    protected ?string $color = 'blue';

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

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getName(),
            'lat' => $this->getLat(),
            'lng' => $this->getLng(),
            'popup' => $this->getPopup(),
            'tooltip'=> $this->getTooltip(),
            'color' => $this->getColor(),
            'callback' => $this->getCallback(),
        ];
    }
}
