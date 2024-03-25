@php
    $heading = $this->getHeading();
    $footer = $this->getFooter();
    $hasBorder = $this->getHasBorder();
    $rounded = $this->getRounded();
@endphp
<x-filament-widgets::widget class="filament-maps-widget">
    <x-filament::card class="filament-maps-card">
        @if ($heading)
            <div @class([
                'px-4 py-2' => $hasBorder,
                'px-6 py-4' => !$hasBorder,
            ])>
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    {{ $heading }}
                </div>
            </div>
        @endif

        <div @class(['px-4 py-2' => $hasBorder])>
            <x-filament-maps::map
                :rounded="$rounded && $hasBorder"
                :tile-layer-url="$this->getTileLayerUrl()"
                :tile-layer-options="$this->getTileLayerOptions()"
                :height="$this->getHeight()"
                :options="$this->getMapOptions()"
                :actions="$this->getCachedMapActions()"
                :extra-attribute-bag="$this->getExtraAttributeBag()"
                :extra-alpine-attribute-bag="$this->getExtraAlpineAttributeBag()"
                :fullpage="$this->isFullPage()" />
        </div>

        @if ($footer)
            <div @class([
                'px-4 py-2' => $hasBorder,
                'px-6 py-4' => !$hasBorder,
            ])>
                {{ $footer }}
            </div>
        @endif
    </x-filament::card>
</x-filament-widgets::widget>

