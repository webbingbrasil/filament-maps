<?php

namespace Webbingbrasil\FilamentMaps\Actions;

use Closure;
use Filament\Support\Actions\Concerns;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Concerns\EvaluatesClosures;

class Action
{
    use Concerns\HasName;
    use Concerns\HasLabel;
    use EvaluatesClosures;
    use Configurable;

    protected string | Closure | null $icon = null;

    protected string | Closure | null $action = '';

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(?string $name = null): static
    {
        $actionClass = static::class;

        $name ??= static::getDefaultName();

        if (blank($name)) {
            throw new Exception("Action of class [$actionClass] must have a unique name, passed to the [make()] method.");
        }

        $static = app($actionClass, ['name' => $name]);
        $static->configure();

        return $static;
    }

    public static function getDefaultName(): ?string
    {
        return null;
    }

    public function icon(string | Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function action(string | Closure | null $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getAction(): string
    {
        return $this->evaluate($this->action);
    }
}
