<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Closure;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Actions\Contracts\HasFormActions;
use Illuminate\Support\Str;
use Webbingbrasil\FilamentMaps\Actions\Action;
use Filament\Support\Exceptions\Cancel;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithMapActions
{
    protected array $cachedMapActions = [];

    public function getModalActionId(): string
    {
        return Str::afterLast($this->getName(), '.') . '.modal-action';
    }

    public function bootedInteractsWithMapActions(): void
    {
        $this->cacheMapActions();
    }

    protected function cacheMapActions(): void
    {
        /** @var array<string, \Filament\Actions\Action | ActionGroup> */
        $actions = Action::configureUsing(
            Closure::fromCallable([$this, 'configureAction']),
            fn (): array => $this->getActions(),
        );

        foreach ($actions as $action) {
            if ($action instanceof ActionGroup) {
                $action->livewire($this);

                /** @var array<string, Action> $flatActions */
                $flatActions = $action->getFlatActions();

                $this->mergeCachedActions($flatActions);
                $this->cachedMapActions[] = $action;

                continue;
            }

            if (! $action instanceof Action) {
                throw new InvalidArgumentException('Header actions must be an instance of ' . Action::class . ', or ' . ActionGroup::class . '.');
            }

            $this->cacheAction($action);
            $this->cachedMapActions[] = $action;
        }
    }

    /**
     * @return array<Action | ActionGroup>
     */
    public function getCachedMapActions(): array
    {
        return $this->cachedMapActions;
    }

    public function actions(array | Closure $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function getActions(): array
    {
        return $this->evaluate($this->actions);
    }
}
