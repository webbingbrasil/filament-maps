@props([
    'tileLayerUrl' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    'tileLayerOptions' => [
        'maxZoom' => 19,
        'attribution' => '© OpenStreetMap',
    ],
    'height' => '400px',
    'options' => [],
    'actions' => [],
    'extraAttributeBag' => '',
    'extraAlpineAttributeBag' => '',
    'modals' => null,
])

@php
    $actions = array_filter(
        $actions,
        fn (\Webbingbrasil\FilamentMaps\Actions\Action $action ): bool => ! $action->isHidden(),
    );
@endphp
<div>
    <div
        {{ $attributes->class('h-full w-full overflow-hidden') }}
        {{ $extraAttributeBag }}>
        <div
            wire:ignore
            x-data="{
                mode: null,

                map: null,

                markers: [],

                polylines: [],

                polygones: [],

                rectangles: [],

                circles: [],

                tileLayers: {},

                markersData: @entangle('markers'),

                polylinesData: @entangle('polylines'),

                polygonesData: @entangle('polygones'),

                rectanglesData: @entangle('rectangles'),

                circlesData: @entangle('circles'),

                init: function () {
                    if (window.filamentMaps['{{ $this->getName() }}']) {
                        window.filamentMaps['{{ $this->getName() }}'].off();
                        window.filamentMaps['{{ $this->getName() }}'].remove();
                    }
                    window.filamentMaps['{{ $this->getName() }}'] = L.map(this.$refs.map, {{ json_encode(array_merge($options, ['zoomControl' => false])) }});
                    this.map = window.filamentMaps['{{ $this->getName() }}'];

                    @foreach((array) $tileLayerUrl as $mode => $url)
                    this.tileLayers['{{ $mode }}'] = L.tileLayer('{{ $url }}', {{ json_encode($tileLayerOptions[$mode] ?? $tileLayerOptions) }});
                    @endforeach

                    let initialMode = '{{ $this->getTileLayerMode() }}';
                    if (
                        document.documentElement.classList.contains('dark') &&
                        this.tileLayers['dark']
                    ) {
                        initialMode = 'dark';
                    }
                    this.setTileLayer(initialMode);

                    this.updateMarkers(this.markersData);

                    this.updatePolylines(this.polylinesData);

                    this.updatePolygones(this.polygonesData);

                    this.updateRectangles(this.rectanglesData);

                    this.updateCircles(this.circlesData);

                    $watch('markersData', (markers) => {
                        this.updateMarkers(markers);
                    });

                    $watch('polylinesData', (polylines) => {
                        this.updatePolylines(polylines);
                    });

                    $watch('polygonesData', (polygones) => {
                        this.updatePolygones(polygones);
                    });

                    $watch('rectanglesData', (rectangles) => {
                        this.updateRectangles(rectangles);
                    });

                    $watch('circlesData', (circles) => {
                        this.updateCircles(circles);
                    });

                    @foreach($actions as $action)
                        this.addAction('{{ $action->getMapActionId()  }}', '{{ $action->getPosition() }}');
                    @endforeach
                },
                setTileLayer: function (mode) {
                    if (this.tileLayers[mode]) {
                        if (this.mode && this.mode != mode && this.map.hasLayer(this.tileLayers[this.mode])) {
                            this.map.removeLayer(this.tileLayers[this.mode]);
                        }

                        this.mode = mode;
                        this.tileLayers[mode].addTo(this.map);

                        return;
                    }
                },
                updateMarkers: function (markers) {
                    if (this.map) {
                        markers.forEach(function (marker) {
                            this.addMarker(marker.id, marker.lat, marker.lng, marker.popup, marker.tooltip, marker.callback);
                        }.bind(this));
                    }
                },
                updatePolylines: function (polyline) {
                    if (this.map) {
                        polyline.forEach(function (polyline) {
                            this.addPolyline(polyline.id, polyline.latlngs, polyline.popup, polyline.tooltip, polyline.options);
                        }.bind(this));
                    }
                },
                updatePolygones: function (polygone) {
                    if (this.map) {
                        polygone.forEach(function (polygone) {
                            this.addPolygone(polygone.id, polygone.latlngs, polygone.popup, polygone.tooltip, polygone.options);
                        }.bind(this));
                    }
                },
                updateRectangles: function (rectangle) {
                    if (this.map) {
                        rectangle.forEach(function (rectangle) {
                            this.addRectangle(rectangle.id, rectangle.bounds, rectangle.popup, rectangle.tooltip, rectangle.options);
                        }.bind(this));
                    }
                },
                updateCircles: function (circle) {
                    if (this.map) {
                        circle.forEach(function (circle) {
                            this.addCircle(circle.id, circle.lat, circle.lng, circle.popup, circle.tooltip, circle.options);
                        }.bind(this));
                    }
                },
                addAction: function (id, position) {
                    var button = new L.Control.Button(L.DomUtil.get(id), { position });
                    button.addTo(this.map);
                },
                addMarker: function (id, lat, lng, popup, tooltip, callback) {
                    this.removeMarker(id);
                    const mMarker = L.marker([lat, lng]).addTo(this.map);
                    if (popup) {
                        mMarker.bindPopup(popup);
                    }
                    if (tooltip) {
                        mMarker.bindTooltip(tooltip);
                    }
                    if (callback) {
                        mMarker.on('click', new Function('var map = this.map; ' + callback).bind(this));
                    }
                    this.markers.push({id, marker: mMarker});
                },
                addPolyline: function (id, latlngs, popup, tooltip, options) {
                    this.removePolyline(id);
                    const pPolyline = L.polyline(latlngs, options).addTo(this.map);
                    if (popup) {
                        pPolyline.bindPopup(popup);
                    }
                    if (tooltip) {
                        pPolyline.bindTooltip(tooltip);
                    }
                    this.polylines.push({id, polyline: pPolyline});
                },
                addPolygone: function (id, latlngs, popup, tooltip, options) {
                    this.removePolyline(id);
                    const pPolygon = L.polygon(latlngs, options).addTo(this.map);
                    if (popup) {
                        pPolygon.bindPopup(popup);
                    }
                    if (tooltip) {
                        pPolygon.bindTooltip(tooltip);
                    }
                    this.polygones.push({id, polygon: pPolygon});
                },
                addRectangle: function (id, bounds, popup, tooltip, options) {
                    this.removeRectangle(id);
                    const rRectangle = L.rectangle(bounds, options).addTo(this.map);
                    if (popup) {
                        rRectangle.bindPopup(popup);
                    }
                    if (tooltip) {
                        rRectangle.bindTooltip(tooltip);
                    }
                    this.rectangles.push({id, rectangle: rRectangle});
                },
                addCircle: function (id, lat, lng, popup, tooltip, options) {
                    this.removeCircle(id);
                    const cCircle = L.circle([lat, lng], options).addTo(this.map);
                    if (popup) {
                        cCircle.bindPopup(popup);
                    }
                    if (tooltip) {
                        cCircle.bindTooltip(tooltip);
                    }
                    this.circles.push({id, marker: cCircle});
                },
                removeMarker: function (id) {
                    const m = this.markers.find(m => m.id === id);
                    if (m) {
                        m.marker.remove();
                        this.markers = this.markers.filter(m => m.id !== id);
                    }
                },
                removePolyline: function (id) {
                    const p = this.polylines.find(p => p.id === id);
                    if (p) {
                        p.polyline.remove();
                        this.polylines = this.polylines.filter(p => p.id !== id);
                    }
                },
                removePolygone: function (id) {
                    const p = this.polylines.find(p => p.id === id);
                    if (p) {
                        p.polyline.remove();
                        this.polylines = this.polylines.filter(p => p.id !== id);
                    }
                },
                removeRectangle: function (id) {
                    const r = this.rectangles.find(r => r.id === id);
                    if (r) {
                        r.rectangle.remove();
                        this.rectangles = this.rectangles.filter(r => r.id !== id);
                    }
                },
                removeCircle: function (id) {
                    const c = this.circles.find(c => c.id === id);
                    if (c) {
                        c.circle.remove();
                        this.circles = this.circles.filter(c => c.id !== id);
                    }
                },
                removeAllMarkers: function () {
                    this.markers.forEach(({marker}) => marker.remove());
                    this.markers = [];
                },
                removeAllPolylines: function () {
                    this.polylines.forEach(({polyline}) => polyline.remove());
                    this.polylines = [];
                },
                removeAllPolygones: function () {
                    this.polygones.forEach(({polygone}) => polygone.remove());
                    this.polygones = [];
                },
                removeAllRectangles: function () {
                    this.rectangles.forEach(({rectangle}) => rectangle.remove());
                    this.rectangles = [];
                },
                removeAllCircles: function () {
                    this.circles.forEach(({circle}) => circle.remove());
                    this.circles = [];
                },
            }"
            {{ $extraAlpineAttributeBag }}
        >
            @if (count($actions))
                <div
                    {{ $attributes->class([
                        'filament-map-actions',
                    ]) }}
                >
                    @foreach ($actions as $action)
                        <div class="filament-map-button justify-center content-center" id="{{ $action->getMapActionId() }}">
                            {{ $action }}
                        </div>
                    @endforeach
                </div>
            @endif
            <div x-ref="map" class="flex-1 relative" style="width: 100%; height: {{ $height }}; z-index: 2"></div>
        </div>
    </div>

    <form wire:submit.prevent="callMountedAction">
        @php
            $action = $this->getMountedAction();
        @endphp

        <x-filament::modal
            id="{{ $this->getModalActionId() }}"
            :wire:key="$action ? $this->id . '.actions.' . $action->getName() . '.modal' : null"
            :visible="filled($action)"
            :width="$action?->getModalWidth()"
            :slide-over="$action?->isModalSlideOver()"
            display-classes="block"
        >
            @if ($action)
                @if ($action->isModalCentered())
                    <x-slot name="heading">
                        {{ $action->getModalHeading() }}
                    </x-slot>

                    @if ($subheading = $action->getModalSubheading())
                        <x-slot name="subheading">
                            {{ $subheading }}
                        </x-slot>
                    @endif
                @else
                    <x-slot name="header">
                        <x-filament::modal.heading>
                            {{ $action->getModalHeading() }}
                        </x-filament::modal.heading>

                        @if ($subheading = $action->getModalSubheading())
                            <x-filament::modal.subheading>
                                {{ $subheading }}
                            </x-filament::modal.subheading>
                        @endif
                    </x-slot>
                @endif

                {{ $action->getModalContent() }}

                @if ($action->hasFormSchema())
                    {{ $this->getMountedActionForm() }}
                @endif

                @if (count($action->getModalActions()))
                    <x-slot name="footer">
                        <x-filament::modal.actions :full-width="$action->isModalCentered()">
                            @foreach ($action->getModalActions() as $modalAction)
                                {{ $modalAction }}
                            @endforeach
                        </x-filament::modal.actions>
                    </x-slot>
                @endif
            @endif
        </x-filament::modal>
    </form>

    {{ $this->modal }}

    @stack('modals')
</div>
