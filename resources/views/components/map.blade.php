@props([
    'tileLayerUrl' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    'tileLayerOptions' => [
        'maxZoom' => 19,
        'attribution' => '© OpenStreetMap',
    ],
    'height' => '400px',
    'options' => [],
    'actions' => [],
    'markers' => [],
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
        {{ $extraAttributeBag }}
        {{ $extraAlpineAttributeBag }}>
        <div
            x-data="{
            map: null,
            markers: [],
            init: function () {
                if (!window.filamentMaps['{{ $this->getName() }}']) {
                    window.filamentMaps['{{ $this->getName() }}'] = L.map(this.$refs.map, {{ json_encode(array_merge($options, ['zoomControl' => false])) }});
                }
                this.map = window.filamentMaps['{{ $this->getName() }}'];

                L.tileLayer('{{ $tileLayerUrl }}', {{ json_encode($tileLayerOptions) }}).addTo(this.map);
                @foreach ($markers as $marker)
                    this.addMarker('{{ $marker->getName()  }}',{{ $marker->getLat() }}, {{ $marker->getLng() }}, {{ json_encode($marker->getPopup()) }}, {{ trim($marker->getCallback()) }});
                @endforeach
                @foreach($actions as $action)
                    this.addAction('{{ $action->getName()  }}', '{{ $action->getPosition() }}');
                @endforeach
            },
            addAction(id, position) {
    {{--            L.easyButton(icon, callback, label, options).addTo(this.map);--}}
                    var button = new L.Control.Button(L.DomUtil.get(id), { position });
                    button.addTo(this.map);
            },
            addMarker(id, lat, lng, info, callback) {
                const marker = L.marker([lat, lng]).addTo(this.map);
                if (info) {
                    marker.bindPopup(info);
                }
                if (callback) {
                    marker.on('click', callback);
                }
                this.markers.push({id, marker});
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
        }">
            @if (count($actions))
                <div wire:ignore
                    {{ $attributes->class([
                        'filament-map-actions',
                    ]) }}
                >
                    @foreach ($actions as $action)
                        {{ $action }}
                    @endforeach
                </div>
            @endif
            <div wire:ignore x-ref="map" class="flex-1 relative z-10" style="width: 100%; height: {{ $height }}"></div>
        </div>
    </div>

    <form wire:submit.prevent="callMountedAction">
        @php
            $action = $this->getMountedAction();
        @endphp

        <x-filament::modal
            id="map-action"
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
