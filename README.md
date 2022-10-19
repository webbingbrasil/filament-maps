# Filament Maps


![](https://banners.beyondco.de/Filament%20Maps.png?theme=light&packageManager=composer+require&packageName=webbingbrasil%2Ffilament-maps&pattern=architect&style=style_1&description=A+leaflet+widget+for+Filament+Admin&md=1&showWatermark=0&fontSize=100px&images=location-marker)

Render map widgets using [Leaflet](https://leafletjs.com/).

- Support for multiple maps on the same page
- Two actions built-in: `CenterMapAction` and `ZoomAction`
- Add Filament Actions directly on the as map control buttons
- Multiple layers support. A DarkModeTile layer is included.

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
    
    protected bool $hasBorder = false;

    public function getMarkers(): array
    {
        return [
            Marker::make('pos2')->lat(-15.7942)->lng(-47.8822)->popup('Hello Brasilia!'),
        ];
    }

    public function getActions(): array
    {
        return [
            Actions\ZoomAction::make(),
            Actions\CenterMapAction::make()->zoom(2),
        ];
    }
}
```

## Map Configuration

For some configuration its possible to use properties:

- `$mapOptions`: array with options available on Leaftlet. See [Leaflet documentation](https://leafletjs.com/reference.html#map-option) for more details.
- `$tileLayerUrl`: string or array with tile layer url`s. Default to [OpenStreetMap](https://www.openstreetmap.org/).
- `$hasBorder`: show a border around the map. Default is `true`.
- `$rounded`: show a rounded border around the map. Default is `true`.
- `$height`: height of the map. Default is `400px`.
- `$heading`: set the heading of the map.
- `$footer`: set the footer of the map.

But you can also use the `setUp` method if you need more control:

```php
public function setUp(): void
{
    $this
        ->height(..)
        ->rounded(..)
        ->mapOptions(..)
        ->tileLayerUrl(...)
        ->markers([
            ...
        ])
        ->actions([
            ..
        ]);
}
```

> The `setUp` method is called on `mount` of the widget. See [livewire lifecycle hooks](https://laravel-livewire.com/docs/2.x/lifecycle-hooks) for more information.

### Global Configuration

If you want to set a default configuration for all instances of the map widget, you can use the `configureUsing` method in a service provider:

```php
MyMap::configureUsing(function (ResellerMap $widget) {
    $widget->mapMarkers([
        Marker::make('id')
            ->lat(51.505)
            ->lng(-0.09)
            ->popup('I am a popup')
            ->tooltip('I am a tooltip'),
    ])
    ->tileLayerUrl('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
    ->tileLayerOptions([
        'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    ])
});
```


## Tile Layers

The map uses OpenStreetMap tiles by default, but you can change it to use any other provider using `$tileLayerUrl` property or `tileLayerUrl` method. It's recommended to also use the `tileLayerOptions` to set correct attributions.

### Multiple Tile Layers

You can also use multiple tile layers:

```php
protected string | array  $tileLayerUrl = [
    'OpenStreetMap' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    'OpenTopoMap' => 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png'
];

protected array $tileLayerOptions = [
    'OpenStreetMap' => [
        'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    ],
    'OpenTopoMap' => [
        'attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, SRTM | Map style © <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
    ],
];
```

And you can use a action to change the tile layer:

```php
Actions\Action::make('mode')
                ->icon('filamentmapsicon-o-square-3-stack-3d')
                ->callback('setTileLayer(mode === "OpenStreetMap" ? "OpenTopoMap" : "OpenStreetMap")'),
```

### Dark Mode

If you want to use a dark mode tile layer, you can use the `HasDarkModeTiles` trait. This trait will automatically set two tiles layers and listen Filament `dark-mode-toggled` event. You can change the default tile layers using the `$lightModeTileLayerUrl` and `$darkModeTileLayerUrl` properties.

## Actions

You can add actions to the map widget. Actions are buttons that can be clicked to perform an action using a JS callback. You can create your own actions or use the ones provided by the package.

### Zoom Action

The `ZoomAction` action will add a button to the map that will zoom in or out the map. You can set the zoom level using the `zoom()` method:

```php
Actions\ZoomAction::make()->zoom(2), // Zoom in/out 2 levels
```

### Center Map Action

This action will center the map on a specific position.

```php
use Webbingbrasil\FilamentMaps\Actions;

public function getActions(): array
{
    return [
        Actions\CenterMapAction::make()->centerTo([51.505, -0.09])->zoom(13),
    ];
}
```

You can also center the map on user position:

```php
Actions\UserPositionAction::make()->centerOnUserPosition()->zoom(13)
```

> Note: The center on user position feature will only work if the user browser supports [Navigator.geolocation](https://developer.mozilla.org/en-US/docs/Web/API/Navigator/geolocation). Also, the user must be on a secure context (HTTPS) and needs to allow access to the location.

### Custom Action

You can create your own actions using `Webbingbrasil\FilamentMaps\Actions\Action`.

For example, a action to add new markers:

```php
use Webbingbrasil\FilamentMaps\Actions;

Actions\Action::make('form')
        ->icon('filamentmapsicon-o-arrows-pointing-in')
        ->form([
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required(),
            Forms\Components\TextInput::make('lat')
                ->label('Latitude')
                ->required(),
            Forms\Components\TextInput::make('lng')
                ->label('Longitude')
                ->required(),
        ])
        ->action(function (array $data, self $livewire) {
            $livewire
                ->addMarker(
                    Marker::make(Str::camel($data['name']))
                        ->lat($data['lat'])
                        ->lng($data['lng'])
                        ->popup($data['name'])
                );
        })
```

In this example we use `addMarker()` method to add a new marker dynamically. You can also use `removeMarker()` and `updateMarker()` methods.

```php
$liveWire->removeMarker('marker-name');
$liveWire->updateMarker(Marker::make('marker-name')->lat(...)->lng(...));
```

> Note: Markers need to have a unique name. If you try to add a marker with the same name as an existing one, the existing one will be replaced.

#### Using JS Callback

This approach is useful if you want to use a custom javascript to manipulate the map without using Livewire.

```php
Actions\Action::make('center')
    ->callback(<<<JS
        map.setView([0,0], 2)
    JS)
```

> Use `map` property to access the Leaflet instance on your action callback.

### Action Position

You can set the position of the action using `position()` method:

```php
$this
    ->actions([
        Actions\CenterMapAction::make()->position('topright'),
    ])
}
```

### Action Icon

You can set the icon of the action using `icon()` method:

```php
Actions\Action::make()->icon('heroicon-o-home')
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
            ->tooltip('I am a tooltip')
            ->callback(<<<JS
                alert('Hello World!');
            JS),
    ])
}
```

>Use `map` to access the Leaflet instance on your action callback.

## Polylines

You can add polylines to the map widget. Polylines are lines on the map drawn on the map between two lat/lng points.
If your have multiple polylines, each polyline must have an unique name.

```php
public function getPolylines(): array
{
    return [
        Polyline::make('polyline')
        ->latlngs([
            [45.51, -122.68],
            [37.77, -122.43],
            [34.04, -118.2]
        ])->options(['color' => 'blue', 'weight' => 5])
        ->tooltip('I am a tooltip')
        ->popup('I am a popup'),
    ];
}
```

You can use options listed at [Leaflet Polyline options](https://leafletjs.com/reference.html#polyline)

### Polylines actions

You can use actions as described above to manipulate polylines:

```php
Actions\Action::make('add line')
    ->tooltip('Add line')
    ->icon('filamentmapsicon-o-map-pin')
    ->form([
        Forms\Components\TextInput::make('name')
            ->label('Name')
            ->required(),
        Forms\Components\Section::make('Start')
            ->schema([
                Forms\Components\TextInput::make('lat1')
                    ->label('Latitude')
                    ->required(),
                Forms\Components\TextInput::make('lng1')
                    ->label('Longitude')
                    ->required(),
            ]),
        Forms\Components\Section::make('End')
            ->schema([
                Forms\Components\TextInput::make('lat2')
                    ->label('Latitude')
                    ->required(),
                Forms\Components\TextInput::make('lng2')
                    ->label('Longitude')
                    ->required(),
            ]),
    ])
    ->action(function (array $data, self $livewire) {
        $livewire
            ->addPolyline(
                Polyline::make(Str::camel($data['name']))
                    ->latlngs([
                        [$data['lat1'], $data['lng1']],
                        [$data['lat2'], $data['lng2']]
                    ])
            );
    })
```

In this example we use `addPolyline()` method to add a new polyline dynamically. You can also use `removePolyline()` and `updatePolyline()` methods.

```php
$livewire->addPolyline(Polyline::make('polyline-name')->latlngs([...])->options([..]));
$livewire->removePolyline('polyline-name');
$livewire->updatePolyline(Polyline::make('polyline-name')->latlngs([...])->options([...]));
```
## Polygones

You can add polygones to the map widget. Polygones are lines on the map drawn on the map between two lat/lng points.
If your have multiple polygones, each polygone must have an unique name.

```php
public function getPolylgones(): array
{
    return [
        Polygone::make('polygone')
        ->latlngs([
            [45.51, -122.68],
            [37.77, -122.43],
            [34.04, -118.2]
        ])->options(['color' => 'blue', 'weight' => 5])
        ->tooltip('I am a tooltip')
        ->popup('I am a popup'),
    ];
}
```

You can use options listed at [Leaflet Polygone options](https://leafletjs.com/reference.html#polygone)

### Polygones actions
```php
$livewire->addPolygone(Polygone::make('polygone-name')->latlngs([...])->options([..]));
$livewire->removePolygone('polygone-name');
$livewire->updatePolygone(Polygone::make('polygone-name')->latlngs([...])->options([...]));
```
## Rectangles

You can add rectangles to the map widget.
If your have multiple rectangles, each rectangle must have an unique name.

```php
public function getRectangles(): array
{
    return [
            Rectangle::make('rectangle')
                ->bounds([
                    [54.559322, -5.767822],
                    [56.1210604, -3.021240]
                ])->tooltip('rectangle')
                ->options(['color' => 'red'])
    ];
}
```

You can use options listed at [Leaflet Rectangle options](https://leafletjs.com/reference.html#rectangle)

### Rectangles actions
```php
$livewire->addRectangle(Polygone::make('rectangle-name')->bounds([...])->options([..]));
$livewire->removeRectangle('rectangle-name');
$livewire->updateRectangle(Polygone::make('rectangle-name')->bounds([...])->options([...]));
```
## Circles

You can add circles to the map widget.
If your have multiple circles, each circle must have an unique name.

```php
public function getCircles(): array
{
    return [
            Circle::make('circle')
                ->lat(-15.7942)
                ->lng(-47.8822)
                ->options(['radius' => 200000])
                ->popup('Hello Brasilia!')
                ->tooltip('test2'),
    ];
}
```

You can use options listed at [Leaflet Circle options](https://leafletjs.com/reference.html#circle)

### Rectangles actions
```php
$livewire->addCircle(Circle::make('circle-name')->lat(...)->lng(...)->options([..]));
$livewire->removeCircle('circle-name');
$livewire->updateCircle(Circle::make('circle-name')->lat(...)->lng(...)->options([...]));
```
## Images

![Header & Footer](./docs/images/image-header-footer.png)
![Compact](./docs/images/image-compact.png)
![Only Header](./docs/images/image-only-header.png)
![Light Mode](./docs/images/image-light-mode.png)
![Dark Mode](./docs/images/image-dark-mode.png)
![Modal Action](./docs/images/image-modal-action.png)

## Credits

-   [Danilo Andrade](https://github.com/dmandrade)

