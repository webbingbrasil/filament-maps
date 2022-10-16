<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css"
      integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14="
      crossorigin=""/>
@if(config('filament-maps.draw'))
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
@endif
<style>
    .filament-map-button button,
    .filament-map-button a,
    .filament-map-button button:hover,
    .filament-map-button a:hover {
        display: flex;
    }
    .filament-maps-popover-arrow.filament-maps-popover-arrow-right {
        top: 50%;
        right: -16px;
        transform: translate(-50%, -50%) rotate(225deg);
    }
    .filament-maps-popover-arrow.filament-maps-popover-arrow-left {
        top: 50%;
        left: 0;
        transform: translate(-50%, -50%) rotate(45deg);
    }
    .filament-maps-popover-arrow {
        height: 16px;
        width: 16px;
        background-color: #fff;
        position: absolute;
        border-bottom: 1px solid rgba(0, 0, 0, 0.3);
        border-left: 1px solid rgba(0, 0, 0, 0.3);
        transform: translate(-50%, -50%) rotate(45deg);
    }
</style>
