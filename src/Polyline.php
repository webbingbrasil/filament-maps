<?php

namespace Webbingbrasil\FilamentMaps;

use Filament\Forms\Components\Concerns\HasOptions;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;

class Polyline implements Arrayable
{
    use EvaluatesClosures;
    use HasOptions;
    protected string $name;

    protected array | Closure $latlngs;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(?string $name = null): static
    {
        $polylineClass = static::class;

        if (blank($name)) {
            throw new Exception("Action of class [$polylineClass] must have a unique name, passed to the [make()] method.");
        }

        return app($polylineClass, ['name' => $name]);
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

    public function latlngs(array| Closure $latlngs): static
    {
        $this->latlngs = $latlngs;

        return $this;
    }

    public function getLatlngs(): array
    {
        return $this->evaluate($this->latlngs);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getName(),
            'latlngs' => $this->getLatlngs(),
            'options' => $this->getOptions(),
        ];
    }
}