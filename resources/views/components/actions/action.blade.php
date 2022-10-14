@props([
    'action',
    'component',
    'icon' => null,
])

@php
    $alpineClickAction = null;
    $wireClickAction = null;
    if ($callback = $action->getCallback()) {
        $alpineClickAction = trim($callback);
    } elseif ($action->getEvent()) {
        $emitArguments = collect([$action->getEvent()])
            ->merge($action->getEventData())
            ->map(fn (mixed $value) => \Illuminate\Support\Js::from($value)->toHtml())
            ->implode(', ');

        $wireClickAction = "\$emit($emitArguments)";
    } elseif ((! $action->getAction()) || $action->getUrl()) {
        $wireClickAction = null;
    } elseif ($action->shouldOpenModal() || ($action->getAction() instanceof \Closure)) {
        $wireClickAction = "mountAction('{$action->getName()}')";
    } else {
        $wireClickAction = $action->getAction();
    }
@endphp

<x-dynamic-component
    :x-on:click="$alpineClickAction"
    :component="$component"
    :dark-mode="config('filament.dark_mode')"
    :attributes="\Filament\Support\prepare_inherited_attributes($attributes)->merge($action->getExtraAttributes())"
    :form="$action->getForm()"
    :tag="$action->getUrl() ? 'a' : 'button'"
    :wire:click="$wireClickAction"
    :href="$action->isEnabled() ? $action->getUrl() : null"
    :target="$action->shouldOpenUrlInNewTab() ? '_blank' : null"
    :type="$action->canSubmitForm() ? 'submit' : 'button'"
    :color="$action->getColor()"
    :key-bindings="$action->getKeybindings()"
    :tooltip="$action->getTooltip()"
    :disabled="$action->isDisabled()"
    :icon="$icon ?? $action->getIcon()"
    :size="$action->getSize()"
    :label-sr-only="$action->isLabelHidden()"
    dusk="filament.admin.action.{{ $action->getName() }}"
>
    {{ $slot }}
</x-dynamic-component>
