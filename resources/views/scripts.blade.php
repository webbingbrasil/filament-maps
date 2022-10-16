<script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
        integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg="
        crossorigin=""></script>
@if(config('filament-maps::draw'))
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
@endif
<script src="https://cdn.jsdelivr.net/gh/jerroydmoore/leaflet-button@master/L.Control.Button.js"></script>
<script>
    L.Tooltip.prototype._updatePosition = function (e) {
        if (!this._map) {
            return
        }

        var pos = this._map.latLngToLayerPoint(this._latlng);
        this._setPosition(pos);
    };
    L.Tooltip.prototype._animateZoom = function (e) {
        if (!this._map) {
            return
        }
        var pos = this._map._latLngToNewLayerPoint(this._latlng, e.zoom, e.center);
        this._setPosition(pos);
    }
    window.filamentMaps = window.filamentMaps || [];
</script>
