# Filament Maps

Render map widgets using [Leaflet](https://leafletjs.com/).

## Installation

```bash
composer require webbingbrasil/filament-maps
```

## Usage
 
Create a widget class and extend `Webbingbrasil\FilamentMaps\Widgets\MapWidget`:

```php
use Webbingbrasil\FilamentMaps\Actions;
use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;

class Map extends MapWidget
{
    protected int | string | array $columnSpan = 2;

    public function mount()
    {
        $this
            ->hasBorder(false)
            ->rounded(false)
            ->mapOptions([
                'center' => [0, 0],
                'zoom' => 2,
            ])
            ->actions([
                Actions\CenterMapAction::make()->position('topright'),
            ])
            ->mapMarkers([
                Marker::make('pos1')->lat(51.505)->lng(-0.09)->popup('I am a popup'),
                Marker::make('pos2')->lat(-15.7942)->lng(-47.8822)->popup('Hello Brasilia!'),
            ]);
    }
}
```

### Tile Layers

The map uses OpenStreetMap tiles by default, but you can change it to use any other provider using `tileLayer()` method:

```php
$this->tileLayer(url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', options: [
    'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
]);
```

### Map Options

You can pass to widget any options available on Leaftlet map constructor. See [Leaflet documentation](https://leafletjs.com/reference.html#map-option) for more details.

## Actions

You can add actions to the map widget. Actions are buttons that can be clicked to perform an action using a JS callback. You can create your own actions or use the ones provided by the package.

### Center Map Action

This action will center the map on a specific position.

```php
use Webbingbrasil\FilamentMaps\Actions;

$this
    ->actions([
        Actions\CenterMapAction::make()->centerTo([51.505, -0.09])->zoom(13),
    ])
}
```

You can also center the map on user position:


```php
    Actions\UserPositionAction::make()->centerOnUserPosition()->zoom(13)
```

### Custom Action

You can create your own actions using `Webbingbrasil\FilamentMaps\Actions\Action`:

```php
use Webbingbrasil\FilamentMaps\Actions;

$this
    ->actions([
        Actions\Action::make('centermap')
            ->label(__('Center Map'))
            ->icon(Blade::render('<x-filamentmapsicon-o-arrows-pointing-in class="p-1" />'))
            ->action(<<<JS
                () => {
                    this.map.setView([51.505, -0.09], 13);
                }
            JS),
    ])
}
```

Use `this.map` to access the Leaflet map instance on your action callback.

### Action Position

You can set the position of the action using `position()` method:

```php
$this
    ->actions([
        Actions\CenterMapAction::make()->position('topright'),
    ])
}
```

## Markers

You can add markers to the map widget. Markers are points on the map that can be clicked to open a info popup or execute a JS callback.

```php
use Webbingbrasil\FilamentMaps\Marker;

$this
    ->mapMarkers([
        Marker::make('id')
            ->lat(51.505)
            ->lng(-0.09)
            ->popup('I am a popup'),
        Marker::make('id')
            ->lat(51.505)
            ->lng(-0.09)
            ->action(<<<JS
                () => {
                    alert('Hello World!');
                }
            JS),
    ])
}
```

Use `this.map` to access the Leaflet map instance on your action callback.

## Credits

-   [Danilo Andrade](https://github.com/dmandrade)

