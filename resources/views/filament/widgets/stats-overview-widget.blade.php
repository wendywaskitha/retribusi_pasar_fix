<x-filament-widgets::widget>
    <div class="grid grid-cols-1 gap-4 sm:gap-5 md:gap-6 sm:grid-cols-3">
        @foreach ($this->getCachedStats() as $stat)
            <x-filament::card>
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $stat->getLabel() }}
                        </h2>

                        <p class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $stat->getValue() }}
                        </p>

                        @if ($stat->getDescription())
                            <p class="mt-2 text-sm font-medium {{ $stat->getColor() }}">
                                @if ($stat->getDescriptionIcon())
                                    <x-filament::icon
                                        :icon="$stat->getDescriptionIcon()"
                                        class="inline-block w-4 h-4 mr-1 -mt-1"
                                    />
                                @endif

                                {{ $stat->getDescription() }}
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center justify-center w-16 h-16 rounded-full bg-{{ $stat->getColor() }}-100 text-{{ $stat->getColor() }}-500">
                        <x-filament::icon
                            :icon="$stat->getIcon() ?? 'heroicon-o-presentation-chart-line'"
                            class="w-8 h-8"
                        />
                    </div>
                </div>
            </x-filament::card>
        @endforeach
    </div>
</x-filament-widgets::widget>
