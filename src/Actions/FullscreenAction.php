<?php

namespace Webbingbrasil\FilamentMaps\Actions;

class FullscreenAction extends Action
{
    protected string $view = 'filament-maps::fullscreen-action';

    protected function setUp(): void
    {
        $this
            ->label(__('Fullscreen'))
            ->icon('filamentmapsicon-fullscreen')
            ->alpineClickHandler('toggleFullscreen()');
    }

    public static function getDefaultName(): ?string
    {
        return 'fullscreen';
    }
}
