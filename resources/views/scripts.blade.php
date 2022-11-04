<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.2/leaflet.min.js"
        integrity="sha512-QPePsP1y4BuymxYZEuQdJ4dsUzZR9MmyrMOLK//zHK4ZoDfMA7zQOJefboRSIm6QKCfzC7djwDOO/+0f72w7Zg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/gh/jerroydmoore/leaflet-button@master/L.Control.Button.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
<script>
    window.filamentMaps = window.filamentMaps || [];


    L.Popup.prototype._animateZoom = function (e) {
        if (!this._map) {
            return
        }
        var pos = this._map._latLngToNewLayerPoint(this._latlng, e.zoom, e.center),
            anchor = this._getAnchor();
        L.DomUtil.setPosition(this._container, pos.add(anchor));
    }

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
    };
</script>
