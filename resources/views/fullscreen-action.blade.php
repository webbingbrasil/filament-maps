@php
    $label = $getLabel();
    $enterFullscreen = $action;
    $exitFullscreen = (clone $action)->icon('filamentmapsicon-fullscreen-exit');
@endphp

<template x-if="!isFullscreen">
    <x-filament-maps::actions.action
        :action="$enterFullscreen"
        label="Enter {{ $label }}"
        component="filament-maps::icon-button"
    />
</template>
<template x-if="isFullscreen">
    <x-filament-maps::actions.action
        :action="$exitFullscreen"
        label="Exit {{ $label }}"
        component="filament-maps::icon-button"
    />
</template>
