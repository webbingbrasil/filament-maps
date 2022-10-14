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

                tileLayers: {},

                markersData: @entangle('markers'),

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
                    $watch('markersData', (markers) => {
                        this.updateMarkers(markers);
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
                            this.addMarker(marker.id, marker.lat, marker.lng, marker.info, marker.callback);
                        }.bind(this));
                    }
                },
                addAction(id, position) {
                    var button = new L.Control.Button(L.DomUtil.get(id), { position });
                    button.addTo(this.map);
                },
                addMarker(id, lat, lng, info, callback) {
                    this.removeMarker(id);
                    const mMarker = L.marker([lat, lng]).addTo(this.map);
                    if (info) {
                        mMarker.bindPopup(info);
                    }
                    if (callback) {
                        mMarker.on('click', callback);
                    }
                    this.markers.push({id, marker: mMarker});
                },
                removeMarker(id) {
                    const m = this.markers.find(m => m.id === id);
                    if (m) {
                        m.marker.remove();
                        this.markers = this.markers.filter(m => m.id !== id);
                    }
                },
                removeAllMarkers() {
                    this.markers.forEach(({marker}) => marker.remove());
                    this.markers = [];
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
