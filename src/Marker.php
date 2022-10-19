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

    protected ?array $icon = null;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    /**
     * @throws Exception
     */
    public static function make(?string $name = null): static
    {
        $markerClass = static::class;

        if (blank($name)) {
            throw new Exception(
                "Action of class [$markerClass] must have a unique name, passed to the [make()] method."
            );
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
        $baseUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img';
        $this->icon(
            $baseUrl . '/marker-icon-2x-' . $color . '.png',
            $baseUrl . '/marker-shadow.png',
            [25, 41],
            [12, 41],
            [1, -34],
            [41, 41]
        );

        return $this;
    }

    public function icon(
        string $iconUrl,
        string $shadowUrl,
        array $iconSize,
        array $iconAnchor,
        array $popupAnchor,
        array $shadowSize
    ): self
    {
        $this->icon = compact(
            'iconUrl',
            'shadowUrl',
            'iconSize',
            'iconAnchor',
            'popupAnchor',
            'shadowSize'
        );

        return $this;
    }

    public function getIcon(): array
    {
        if (is_null($this->icon)) {
            $this->color(self::COLOR_BLUE);
        }

        return $this->icon;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getName(),
            'lat' => $this->getLat(),
            'lng' => $this->getLng(),
            'popup' => $this->getPopup(),
            'tooltip'=> $this->getTooltip(),
            'icon' => $this->getIcon(),
            'callback' => $this->getCallback(),
        ];
    }
}
