<?php

namespace Webbingbrasil\FilamentMaps;

use Filament\Forms\Components\Concerns\HasOptions;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;
use Webbingbrasil\FilamentMaps\Concerns\HasPopup;
use Webbingbrasil\FilamentMaps\Concerns\HasTooltip;

class Rectangle implements Arrayable
{
    use EvaluatesClosures;
    use HasOptions;
    use HasPopup;
    use HasTooltip;

    protected string $name;

    protected array | Closure $bounds;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(?string $name = null): static
    {
        $ractangleClass = static::class;

        if (blank($name)) {
            throw new Exception("Action of class [$ractangleClass] must have a unique name, passed to the [make()] method.");
        }

        return app($ractangleClass, ['name' => $name]);
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

    public function bounds(array| Closure $bounds): static
    {
        $this->bounds = $bounds;

        return $this;
    }

    public function getBounds(): array
    {
        return $this->evaluate($this->bounds);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getName(),
            'bounds' => $this->getBounds(),
            'options' => $this->getOptions(),
            'popup' => $this->getPopup(),
            'tooltip'=> $this->getTooltip(),
        ];
    }
}