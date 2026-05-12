@props([
    'hasBorder' => true,
    'heading' => null,
    'footer' => null,
])

@php
    $hasHeading = filled($heading);
    $hasHeader = $hasHeading;

    $hasFooter = filled($footer);
@endphp

<section class="fi-maps-card flex flex-col h-full overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    @if ($hasHeader)
        <header @class([
            'fi-maps-card-header flex flex-col gap-3',
            match ($hasBorder) {
                true => 'px-4 py-2.5',
                false => 'px-6 py-4',
            }
        ])>
            <div class="flex items-center gap-3">
                @if ($hasHeading)
                    <div class="grid flex-1 gap-y-1">
                        @if ($hasHeading)
                            <x-filament-maps::card.heading>
                                {{ $heading }}
                            </x-filament-maps::card.heading>
                        @endif
                    </div>
                @endif
            </div>
        </header>
    @endif

    <div
        @class([
            'fi-maps-card-content flex-1',
            match ($hasBorder) {
                true => 'p-6',
                false => '',
            },
        ])
    >
        {{ $slot }}
    </div>

    @if ($hasFooter)
        <footer @class([
            'fi-maps-card-footer border-t border-gray-200 dark:border-white/10',
            match ($hasBorder) {
                true => 'px-4 py-2.5',
                false => 'px-6 py-4',
            }
        ])>
            {{ $footer }}
        </footer>
    @endif
</section>
