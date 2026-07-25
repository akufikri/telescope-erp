<x-filament-panels::page>
    <div class="flex flex-col gap-8">
        {{-- Module User Guides --}}
        <div class="flex flex-col gap-4">
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                {{ __('support::filament/pages/help.guides.group') }}
            </span>

            {{ $this->guidesInfolist }}
        </div>

        {{-- Contact Banner --}}
        <div class="from-primary-600 to-primary-500 flex flex-wrap items-center justify-between gap-4 rounded-xl bg-gradient-to-r p-6">
            <div class="flex items-center gap-4">
                <div class="size-12 flex shrink-0 items-center justify-center rounded-full bg-white/20">
                    <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="size-6 text-white" />
                </div>

                <div>
                    <div class="text-base font-semibold text-white">
                        {{ __('support::filament/pages/help.contact.title') }}
                    </div>
                    <div class="text-sm text-white/80">
                        {{ __('support::filament/pages/help.contact.description') }}
                    </div>
                </div>
            </div>

            <div class="flex shrink-0 flex-wrap items-center gap-3">
                <a
                    href="https://telescope-erp.com/contacts"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-primary-600 inline-flex shrink-0 items-center gap-2 whitespace-nowrap rounded-lg bg-white px-5 py-2.5 text-sm font-semibold shadow-sm transition hover:bg-gray-100"
                >
                    <span>{{ __('support::filament/pages/help.contact.button') }}</span>
                    <x-filament::icon icon="heroicon-m-arrow-right" class="size-4" />
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page>
