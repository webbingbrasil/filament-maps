<?php

namespace Webbingbrasil\FilamentMaps\Actions;

class FullpageAction extends Action
{
    protected string $view = 'filament-maps::fullscreen-action';

    protected function setUp(): void
    {
        $this->label(__('Fullpage'));
        $this->icon('filamentmapsicon-fullscreen');
        $this->alpineClickHandler('toggleFullpage');
    }

    public static function getDefaultName(): ?string
    {
        return 'fullpage';
    }
}
