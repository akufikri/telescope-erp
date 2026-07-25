<x-filament::section class="h-full">
    <div class="flex h-full flex-col gap-4">
        <div class="flex items-center gap-3">
            <div class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-600 dark:text-primary-400">
                <x-filament::icon :icon="$card['icon']" class="size-6" />
            </div>

            <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                {{ $card['title'] }}
            </h3>
        </div>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $card['description'] }}
        </p>

        @if (!empty($card['steps']))
            <div class="flex flex-col gap-2">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                    {{ __('support::filament/pages/help.guides.how_to_use') }}
                </span>

                <ol class="flex flex-col gap-1.5 list-decimal list-inside text-sm text-gray-600 dark:text-gray-300">
                    @foreach ($card['steps'] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </div>
        @endif
    </div>
</x-filament::section>
