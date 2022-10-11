<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Closure;

trait HasMapActions
{
    protected array | Closure $actions = [];

    public function actions(array | Closure $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function getActions()
    {
        return $this->evaluate($this->actions);
    }
}
