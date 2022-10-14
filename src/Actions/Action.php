<?php

namespace Webbingbrasil\FilamentMaps\Actions;

use Closure;
use Filament\Pages\Actions\Modal\Actions\Action as ModalAction;
use Filament\Support\Actions\Action as BaseAction;
use Filament\Support\Actions\Concerns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Webbingbrasil\FilamentMaps\Actions\Concerns\HasCallback;
use Webbingbrasil\FilamentMaps\Actions\Concerns\BelongsToLivewire;

class Action extends BaseAction
{
    use Concerns\CanBeDisabled;
    use Concerns\CanBeOutlined;
    use Concerns\CanOpenUrl;
    use Concerns\CanEmitEvent;
    use Concerns\CanSubmitForm;
    use Concerns\HasKeyBindings;
    use Concerns\HasTooltip;
    use Concerns\InteractsWithRecord;
    use HasCallback;
    use BelongsToLivewire;

    protected string $position = 'topleft';

    protected string $view = 'filament-maps::button-action';

    protected string | Closure | null  $color = 'secondary';

    protected function getLivewireCallActionName(): string
    {
        return 'callMountedAction';
    }

    protected static function getModalActionClass(): string
    {
        return ModalAction::class;
    }

    public static function makeModalAction(string $name): ModalAction
    {
        /** @var ModalAction $action */
        $action = parent::makeModalAction($name);

        return $action;
    }

    protected function getDefaultEvaluationParameters(): array
    {
        return array_merge(parent::getDefaultEvaluationParameters(), [
            'record' => $this->resolveEvaluationParameter(
                'record',
                fn (): ?Model => $this->getRecord(),
            ),
        ]);
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
