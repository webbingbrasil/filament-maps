@php
 $zoomIn = $action;
 $zoomOut = (clone $action)->icon('heroicon-o-minus');
@endphp

<div class="filament-map-button justify-center content-center" id="{{ $action->getName() }}">
    <x-filament-maps::actions.action
        :action="$zoomIn->increment()"
        label="Zoom in"
        component="filament-maps::icon-button"
        class="filament-page-icon-button-action"
        style="border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;"
    />
    <x-filament-maps::actions.action
        :action="$zoomOut->decrement()"
        label="Zoom out"
        component="filament-maps::icon-button"
        class="filament-page-icon-button-action"
        style="border-top-right-radius: 0px;border-top-left-radius: 0px;"
    />
</div>
