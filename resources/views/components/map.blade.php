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
    'rounded' => true,
    'fullpage' => false,
    'pollingInterval' => null,
])

@php
    $actions = array_filter(
        $actions,
        fn (\Webbingbrasil\FilamentMaps\Actions\Action $action ): bool => ! $action->isHidden(),
    );
@endphp
<div
    @class([
        'filament-map h-full',
    ])

    @if ($pollingInterval)
        wire:poll.{{ $pollingInterval }}
    @endif
>
    <div
        {{ $attributes->class([
            'h-full w-full overflow-hidden',
            'rounded-xl' => $rounded
        ]) }}
        {{ $extraAttributeBag }}>
        <div
            class="h-full"
            wire:ignore
            x-data="{
                mode: null,

                map: null,

                fullpage: false,

                isFullscreen: false,

                mapDefaultHeight: '{{ $height }}',

                mapFullpageHeight: 0,

                markers: [],

                markerClusters: [],

                polylines: [],

                polygones: [],

                rectangles: [],

                circles: [],

                tileLayers: {},

                fitBounds: @entangle('fitBounds'),

                centerTo: @entangle('centerTo'),

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
                    window.filamentMaps['{{ $this->getName() }}'] = leaflet.map(this.$refs.map, {{ json_encode(array_merge($options, ['zoomControl' => false])) }});
                    this.map = window.filamentMaps['{{ $this->getName() }}'];

                    if (this.fitBounds) {
                        this.map.fitBounds(this.fitBounds);
                    }

                    @foreach((array) $tileLayerUrl as $mode => $url)
                    this.tileLayers['{{ $mode }}'] = leaflet.tileLayer('{{ $url }}', {{ json_encode($tileLayerOptions[$mode] ?? $tileLayerOptions) }});
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

                    $watch('fitBounds', (bounds) => {
                        if (bounds) {
                            this.map.fitBounds(bounds);
                        }
                    });

                    $watch('centerTo', (center) => {
                        this.map.setView(center.location, center.zoom);
                    });

                    const resizeObserver = new ResizeObserver(() => {
                      this.map.invalidateSize();
                    });

                    resizeObserver.observe(this.map._container);

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

                    const topbarHeight = document.querySelector('.fi-topbar').offsetHeight;
                    this.mapFullpageHeight = (window.innerHeight - topbarHeight) + 'px';

                    var fullscreenchange;

                    if ('onfullscreenchange' in document) {
                        fullscreenchange = 'fullscreenchange';
                    } else if ('onmozfullscreenchange' in document) {
                        fullscreenchange = 'mozfullscreenchange';
                    } else if ('onwebkitfullscreenchange' in document) {
                        fullscreenchange = 'webkitfullscreenchange';
                    } else if ('onmsfullscreenchange' in document) {
                        fullscreenchange = 'MSFullscreenChange';
                    }
                    if (fullscreenchange) {
                        document.addEventListener(fullscreenchange, function () {
                            var fullscreenElement =
                                document.fullscreenElement ||
                                document.mozFullScreenElement ||
                                document.webkitFullscreenElement ||
                                document.msFullscreenElement;

                            if (typeof fullscreenElement === 'undefined' && this.isFullscreen) {
                                this.isFullscreen = false;
                                this.$refs.map.style.height = this.fullpage ? this.mapFullpageHeight : this.mapDefaultHeight;
                            }
                        }.bind(this));
                    }

                    @foreach($actions as $action)
                        this.addAction('{{ $action->getMapActionId()  }}', '{{ $action->getPosition() }}');
                    @endforeach
                    @if($fullpage)
                        this.toggleFullpage();
                    @endif
                },
                toggleFullscreen: function () {
                    var container = this.$refs.map.parentElement;
                    while (container) {
                        if (container.classList.contains('filament-map')) {
                            break;
                        }
                        container = container.parentElement;
                    }

                    if (this.isFullscreen) {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.webkitCancelFullScreen) {
                            document.webkitCancelFullScreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                        this.isFullscreen = false;
                        this.$refs.map.style.height = this.fullpage ? this.mapFullpageHeight : this.mapDefaultHeight;
                        return;
                    }

                    if (container.requestFullscreen) {
                        container.requestFullscreen();
                    } else if (container.mozRequestFullScreen) {
                        container.mozRequestFullScreen();
                    } else if (container.webkitRequestFullscreen) {
                        container.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    } else if (container.msRequestFullscreen) {
                        container.msRequestFullscreen();
                    }
                    this.$refs.map.style.height = '100vh';
                    this.isFullscreen = true;
                },
                toggleFullpage: function () {
                    if (this.fullpage == false) {
                        document.querySelector('.fi-main-ctn').style.height = '100vh';
                        document.querySelector('.fi-main').style.position = 'relative';
                        document.querySelector('.fi-main').style.overflow = 'hidden';
                        this.$refs.map.style.height = this.mapFullpageHeight;
                        this.$refs.map.style.minHeight = this.mapFullpageHeight;
                        this.$refs.map.style.position = 'absolute';
                        this.$refs.map.style.top = '0';
                        this.$refs.map.style.left = '0';
                        this.$refs.map.style.zIndex = '5';
                        this.fullpage = true;
                        return;
                    }

                    document.querySelector('.fi-main-ctn').style.maxHeight = null;
                    document.querySelector('.fi-main').style.position = '';
                    document.querySelector('.fi-main').style.overflow = null;
                    this.$refs.map.style.height = this.mapDefaultHeight;
                    this.$refs.map.style.minHeight = '100%';
                    this.$refs.map.style.position = '';
                    this.$refs.map.style.top = 'inherit';
                    this.fullpage = false;
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
                        this.markerClusters.forEach((cluster) => {
                            this.map.removeLayer(cluster);
                        });
                        this.markers.forEach((marker) => {
                            if (! markers.find((m) => m.id == marker.id)) {
                                this.removeMarker(marker.id);
                            }
                        });

                        markers.forEach(function (marker) {
                            if (marker.type === 'marker') {
                                this.addMarker(marker.id, marker.lat, marker.lng, marker.popup, marker.tooltip, marker.icon, marker.callback);
                            }
                            if (marker.type === 'cluster') {
                                this.addMarkerCluster(marker.markers);
                            }
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
                    var button = new L.Control.Button(leaflet.DomUtil.get(id), { position });
                    button.addTo(this.map);
                },
                prepareMarker: function (id, lat, lng, popup, tooltip, icon, callback) {
                    this.removeMarker(id);
                    var options = {};
                    if (icon) {
                        options.icon = leaflet.icon(icon);
                    }
                    const mMarker = leaflet.marker([lat, lng], options);
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
                    return mMarker;
                },
                addMarker: function (id, lat, lng, popup, tooltip, icon, callback) {
                    this.prepareMarker(id, lat, lng, popup, tooltip, icon, callback).addTo(this.map);
                },
                addMarkerCluster: function (markers) {
                    const mMarkers = [];
                    const mMarkerCluster = leaflet.markerClusterGroup().addTo(this.map);
                    markers.forEach(function ({id, lat, lng, popup, tooltip, icon, callback}) {
                        const mMarker = this.prepareMarker(id, lat, lng, popup, tooltip, icon, callback);
                        mMarkers.push(mMarker);
                    }.bind(this));
                    mMarkerCluster.addLayers(mMarkers);
                    this.markerClusters.push(mMarkerCluster);
                },
                addPolyline: function (id, latlngs, popup, tooltip, options) {
                    this.removePolyline(id);
                    const pPolyline = leaflet.polyline(latlngs, options).addTo(this.map);
                    if (popup) {
                        pPolyline.bindPopup(popup);
                    }
                    if (tooltip) {
                        pPolyline.bindTooltip(tooltip);
                    }
                    this.polylines.push({id, polyline: pPolyline});
                },
                addPolygone: function (id, latlngs, popup, tooltip, options) {
                    this.removePolygone(id);
                    const pPolygon = leaflet.polygon(latlngs, options).addTo(this.map);
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
                    const rRectangle = leaflet.rectangle(bounds, options).addTo(this.map);
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
                    const cCircle = leaflet.circle([lat, lng], options).addTo(this.map);
                    if (popup) {
                        cCircle.bindPopup(popup);
                    }
                    if (tooltip) {
                        cCircle.bindTooltip(tooltip);
                    }
                    this.circles.push({id, circle: cCircle});
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
                    const p = this.polygones.find(p => p.id === id);
                    if (p) {
                        p.polygon.remove();
                        this.polygones = this.polygones.filter(p => p.id !== id);
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
                        'absolute z-10 p-2 space-y-2 filament-map-actions',
                    ]) }}
                >
                    @foreach ($actions as $action)
                        <div class="filament-map-button justify-center content-center" id="{{ $action->getMapActionId() }}">
                            {{ $action }}
                        </div>
                    @endforeach
                </div>
            @endif
            <div x-ref="map" class="filament-map-container flex-1 relative" style="width: 100%; height: {{ $height }}; min-height: 100%; z-index: 0"></div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
