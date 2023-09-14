@php
    $heading = $this->getHeading();
    $footer = $this->getFooter();
    $hasBorder = $this->getHasBorder();
    $rounded = $this->getRounded();
@endphp
<x-filament-widgets::widget class="filament-maps-widget">
    <div @class([
        'bg-white rounded-xl shadow overflow-hidden',
        'p-2 space-y-2' => $hasBorder,
        'dark:border-gray-600 dark:bg-gray-800' => config('filament.dark_mode'),
    ])>
        @if ($heading)
            <div @class([
                'px-4 py-2' => $hasBorder,
                'px-6 py-4' => !$hasBorder,
            ])>
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <x-filament::card.heading>
                        {{ $heading }}
                    </x-filament::card.heading>
                </div>
            </div>
        @endif

        @if ($heading)
            <x-filament::hr />
        @endif

        <div @class(['px-4 py-2' => $hasBorder])>
            <x-filament-maps::map
                :rounded="$rounded && $hasBorder"
                :tile-layer-url="$this->getTileLayerUrl()"
                :tile-layer-options="$this->getTileLayerOptions()"
                :height="$this->getHeight()"
                :options="$this->getMapOptions()"
                :actions="$this->getCachedActions()"
                :extra-attribute-bag="$this->getExtraAttributeBag()"
                :extra-alpine-attribute-bag="$this->getExtraAlpineAttributeBag()"
                :fullpage="$this->isFullPage()" />
        </div>

        @if ($footer)
            <x-filament::hr />
        @endif

        @if ($footer)
            <div @class([
                'px-4 py-2' => $hasBorder,
                'px-6 py-4' => !$hasBorder,
            ])>
                {{ $footer }}
            </div>
        @endif
    </div>
</x-filament-widgets::widget>

