@php
    $zoomIn = $action;
    $zoomOut = (clone $action)->icon('heroicon-o-minus');
@endphp

<x-filament-maps::actions.action
    :action="$zoomIn->increment()"
    label="Zoom in"
    component="filament-maps::icon-button"
    style="border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;"
/>
<x-filament-maps::actions.action
    :action="$zoomOut->decrement()"
    label="Zoom out"
    component="filament-maps::icon-button"
    style="border-top-right-radius: 0px;border-top-left-radius: 0px;"
/>
