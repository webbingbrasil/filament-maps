<?php

namespace Webbingbrasil\FilamentMaps\Actions;

use Closure;
use Filament\Support\Actions\Concerns;
use Filament\Support\Concerns\Configurable;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Blade;

class FullpageAction extends Action
{
    protected string $view = 'filament-maps::fullscreen-action';

    protected function setUp(): void
    {
        $this->label(__('Fullpage'));
        $this->icon('filamentmapsicon-fullscreen');
        $this->callback('toggleFullpage');
    }

    public static function getDefaultName(): ?string
    {
        return 'fullpage';
    }
}
