<script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
        integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg="
        crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/gh/jerroydmoore/leaflet-button@master/L.Control.Button.js"></script>
<script>
    L.Popup.prototype._animateZoom = function (e) {
        if (!this._map) {
            return
        }
        var pos = this._map._latLngToNewLayerPoint(this._latlng, e.zoom, e.center),
            anchor = this._getAnchor();
        DomUtil.setPosition(this._container, pos.add(anchor));
    }
    window.filamentMaps = window.filamentMaps || [];
</script>
