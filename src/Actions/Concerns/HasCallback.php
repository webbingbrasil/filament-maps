<?php

namespace Webbingbrasil\FilamentMaps\Actions\Concerns;

use Closure;

trait HasCallback
{
    protected Closure | string | null $callback = null;

    public function callback(Closure | string | null $callback): static
    {
        $this->callback = $callback;

        return $this;
    }

    public function getCallback(): ?string
    {
        return $this->evaluate($this->callback);
    }
}
