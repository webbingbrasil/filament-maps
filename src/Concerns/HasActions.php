<?php

namespace Webbingbrasil\FilamentMaps\Concerns;

use Closure;
use Filament\Forms;
use Filament\Pages\Contracts\HasFormActions;
use Webbingbrasil\FilamentMaps\Actions\Action;
use Filament\Support\Exceptions\Cancel;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

trait HasActions
{
    use Forms\Concerns\InteractsWithForms;

    public $mountedAction = null;

    protected ?array $cachedActions = null;

    protected array | Closure $actions = [];

    public function callMountedAction(?string $arguments = null)
    {
        $action = $this->getMountedAction();

        if (! $action || $action->isDisabled()) {
            return;
        }

        $action->arguments($arguments ? json_decode($arguments, associative: true) : []);

        $form = $this->getMountedActionForm();

        $result = null;

        try {
            if ($action->hasForm()) {
                $action->callBeforeFormValidated();

                $action->formData($form->getState());

                $action->callAfterFormValidated();
            }

            $action->callBefore();

            $result = $action->call([
                'form' => $form,
            ]);

            $result = $action->callAfter() ?? $result;
        } catch (Halt $exception) {
            return;
        } catch (Cancel $exception) {
        }

        $this->mountedAction = null;

        $action->resetArguments();
        $action->resetFormData();

        $this->dispatchBrowserEvent('close-modal', [
            'id' => 'map-action',
        ]);

        return $result;
    }

    public function mountAction(string $name)
    {
        $this->mountedAction = $name;

        $action = $this->getMountedAction();

        if (! $action || $action->isDisabled()) {
            return;
        }

        $this->cacheForm(
            'mountedActionForm',
            fn () => $this->getMountedActionForm(),
        );

        try {
            if ($action->hasForm()) {
                $action->callBeforeFormFilled();
            }

            $action->mount([
                'form' => $this->getMountedActionForm(),
            ]);

            if ($action->hasForm()) {
                $action->callAfterFormFilled();
            }
        } catch (Halt $exception) {
            return;
        } catch (Cancel $exception) {
            $this->mountedAction = null;

            return;
        }

        if (! $action->shouldOpenModal()) {
            return $this->callMountedAction();
        }

        $this->resetErrorBag();

        $this->dispatchBrowserEvent('open-modal', [
            'id' => 'map-action',
        ]);
    }

    protected function getCachedActions(): array
    {
        if ($this->cachedActions === null) {
            $this->cacheActions();
        }

        return $this->cachedActions;
    }

    protected function cacheActions(): void
    {
        $actions = Action::configureUsing(
            Closure::fromCallable([$this, 'configureAction']),
            fn (): array => $this->getActions(),
        );

        $this->cachedActions = [];

        foreach ($actions as $index => $action) {
            $action->livewire($this);

            $this->cachedActions[$action->getName()] = $action;
        }
    }

    protected function configureAction(Action $action): void
    {
    }

    public function getMountedAction(): ?Action
    {
        if (! $this->mountedAction) {
            return null;
        }

        $action = $this->getCachedAction($this->mountedAction);

        if ($action) {
            return $action;
        }

        if (! $this instanceof HasFormActions) {
            return null;
        }

        return $this->getCachedFormAction($this->mountedAction);
    }

    protected function getHasActionsForms(): array
    {
        return [
            'mountedActionForm' => $this->getMountedActionForm(),
        ];
    }

    public function getMountedActionForm(): ?Forms\ComponentContainer
    {
        $action = $this->getMountedAction();

        if (! $action) {
            return null;
        }

        if ((! $this->isCachingForms) && $this->hasCachedForm('mountedActionForm')) {
            return $this->getCachedForm('mountedActionForm');
        }

        return $this->makeForm()
            ->schema($action->getFormSchema())
            ->statePath('mountedActionData')
            ->model($this->getMountedActionFormModel())
            ->context($this->mountedAction);
    }

    protected function getMountedActionFormModel(): Model | string | null
    {
        return null;
    }

    public function getCachedAction(string $name): ?Action
    {
        $actions = $this->getCachedActions();

        $action = $actions[$name] ?? null;

        if ($action) {
            return $action;
        }

        return null;
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
