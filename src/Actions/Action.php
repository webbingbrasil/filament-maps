<?php

namespace Webbingbrasil\FilamentMaps\Actions;

use Filament\Actions\Action as BaseAction;
use Filament\Actions\Concerns;
use Illuminate\Support\Str;

class Action extends BaseAction
{
    use Concerns\CanBeDisabled;
    use Concerns\CanBeOutlined;
    use Concerns\CanOpenUrl;
    use Concerns\CanDispatchEvent;
    use Concerns\CanSubmitForm;
    use Concerns\HasKeyBindings;
    use Concerns\HasTooltip;
    use Concerns\InteractsWithRecord;

    protected string $position = 'topleft';

    protected string $view = 'filament-maps::button-action';

    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): array
    {
        return match ($parameterName) {
            'record' => [$this->getRecord()],
            default => parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName),
        };
    }

    public function position(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getMapActionId(): string
    {
        return Str::afterLast($this->getLivewire()->getName(), '.') . '.' . $this->getName();
    }
}
