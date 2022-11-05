<?php

namespace Webbingbrasil\FilamentMaps;

use Closure;
use Exception;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;
use Webbingbrasil\FilamentMaps\Concerns\HasPopup;
use Webbingbrasil\FilamentMaps\Concerns\HasTooltip;

class MarkerCluster implements Arrayable
{
    use EvaluatesClosures;

    protected array $markers = [];

    final public function __construct(array $makers)
    {
        $this->markers = $makers;
    }

    /**
     * @throws Exception
     */
    public static function make(array $makers): static
    {
        $markerClass = static::class;

        return app($markerClass, ['makers' => $makers]);
    }

    public function toArray(): array
    {
        return [
            'type' => 'cluster',
            'markers' => array_map(
                function (array | Marker $marker) {
                    return $marker instanceof Marker ? $marker->toArray() : $marker;
                },
                $this->markers
            ),
        ];
    }
}
