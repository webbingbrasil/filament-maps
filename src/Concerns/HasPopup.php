<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

trait HasPopup
{
    protected string | Closure | null $popup = null;

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