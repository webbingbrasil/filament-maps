<script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
        integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg="
        crossorigin=""></script>
@if(config('filament-maps.draw'))
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
@endif
<script src="https://cdn.jsdelivr.net/gh/jerroydmoore/leaflet-button@master/L.Control.Button.js"></script>
<script>
    window.filamentMaps = window.filamentMaps || [];

    L.Tooltip.prototype._updatePosition = function (e) {
        if (!this._map) {
            return
        }

        var pos = this._map.latLngToLayerPoint(this._latlng);
        this._setPosition(pos);
    };

    L.Popup.prototype._animateZoom = function (e) {
        if (!this._map) {
            return
        }
        var pos = this._map._latLngToNewLayerPoint(this._latlng, e.zoom, e.center),
            anchor = this._getAnchor();
        DomUtil.setPosition(this._container, pos.add(anchor));
    }

    L.Tooltip.prototype._animateZoom = function (e) {
        if (!this._map) {
            return
        }
        var pos = this._map._latLngToNewLayerPoint(this._latlng, e.zoom, e.center);
        this._setPosition(pos);
    }

    L.Control.PMButton.prototype.onAdd = function (map) {
        this._map = map;
        if (!this._map.pm.Toolbar.options.oneBlock) {
            if (this._button.tool === 'edit') {
                this._container = this._map.pm.Toolbar.editContainer;
            } else if (this._button.tool === 'options') {
                this._container = this._map.pm.Toolbar.optionsContainer;
            } else if (this._button.tool === 'custom') {
                this._container = this._map.pm.Toolbar.customContainer;
            } else {
                this._container = this._map.pm.Toolbar.drawContainer;
            }
        } else {
            this._container = this._map.pm.Toolbar._createContainer(
                this.options.position
            );
        }
        this.buttonsDomNode = this._makeButton(this._button);
        this._container.classList.remove('leaflet-bar');
        this._container.classList.add('rounded-lg', 'border', 'border-gray-300', 'overflow-hidden');
        this._container.appendChild(this.buttonsDomNode);

        return this._container;
    };

    L.Control.PMButton.prototype._makeButton = function (button) {
        const pos = this.options.position.indexOf('right') > -1 ? 'pos-right' : '';

        // button container
        const buttonContainer = L.DomUtil.create(
            'div',
            `button-container  ${pos}`,
            this._container
        );

        if (button.title) {
            buttonContainer.setAttribute('title', button.title);
        }

        // the button itself
        const newButton = L.DomUtil.create(
            'button',
            'filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium ' +
            'border-b border-gray-300 transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 ' +
            'focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-2 text-sm text-gray-800 bg-white ' +
            'hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 ' +
            'focus:border-primary-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-600 ' +
            'dark:hover:border-gray-500 dark:text-gray-200 dark:focus:text-primary-400 dark:focus:border-primary-400 ' +
            'dark:focus:bg-gray-800 filament-page-icon-button-action',
            buttonContainer
        );
        // newButton.setAttribute('role', 'button');
        newButton.setAttribute('tabindex', '0');
        newButton.href = '#';

        // the buttons actions
        const actionContainer = L.DomUtil.create(
            'div',
            `leaflet-pm-actions-container ${pos}`,
            buttonContainer
        );

        const activeActions = button.actions;

        const actions = {
            cancel: {
                text: 'actions.cancel',
                onClick() {
                    this._triggerClick();
                },
            },
            finishMode: {
                text: 'actions.finish',
                onClick() {
                    this._triggerClick();
                },
            },
            removeLastVertex: {
                text: 'actions.removeLastVertex',
                onClick() {
                    this._map.pm.Draw[button.jsClass]._removeLastVertex();
                },
            },
            finish: {
                text: 'actions.finish',
                onClick(e) {
                    this._map.pm.Draw[button.jsClass]._finishShape(e);
                },
            },
        };

        activeActions.forEach((_action) => {
            const name = typeof _action === 'string' ? _action : _action.name;
            let action;
            if (actions[name]) {
                action = actions[name];
            } else if (_action.text) {
                action = _action;
            } else {
                return;
            }
            const actionNode = L.DomUtil.create(
                'a',
                `leaflet-pm-action ${pos} action-${name}`,
                actionContainer
            );
            actionNode.setAttribute('role', 'button');
            actionNode.setAttribute('tabindex', '0');
            actionNode.href = '#';

            actionNode.innerHTML = action.text;

            L.DomEvent.disableClickPropagation(actionNode);
            L.DomEvent.on(actionNode, 'click', L.DomEvent.stop);

            if (!button.disabled) {
                if (action.onClick) {
                    const actionClick = (e) => {
                        // is needed to prevent scrolling when clicking on a-element with href="a"
                        e.preventDefault();
                        let btnName = '';
                        const { buttons } = this._map.pm.Toolbar;
                        for (const btn in buttons) {
                            if (buttons[btn]._button === button) {
                                btnName = btn;
                                break;
                            }
                        }
                        this._fireActionClick(action, btnName, button);
                    };

                    L.DomEvent.addListener(actionNode, 'click', actionClick, this);
                    L.DomEvent.addListener(actionNode, 'click', action.onClick, this);
                }
            }
        });

        if (button.toggleStatus) {
            L.DomUtil.addClass(buttonContainer, 'active');
        }

        const image = L.DomUtil.create('div', 'bg-contain bg-center w-5 h-5 min-h-full', newButton);

        if (button.iconUrl) {
            image.setAttribute('src', button.iconUrl);
        }
        if (button.className) {
            L.DomUtil.addClass(image, button.className);
        }
        image.classList.remove('control-icon');

        L.DomEvent.disableClickPropagation(newButton);
        L.DomEvent.on(newButton, 'click', L.DomEvent.stop);

        if (!button.disabled) {
            // before the actual click, trigger a click on currently toggled buttons to
            // untoggle them and their functionality
            L.DomEvent.addListener(newButton, 'click', this._onBtnClick, this);
            L.DomEvent.addListener(newButton, 'click', this._triggerClick, this);
        }

        if (button.disabled) {
            L.DomUtil.addClass(newButton, 'pm-disabled');
            newButton.setAttribute('aria-disabled', 'true');
        }

        return buttonContainer;
    };
</script>
