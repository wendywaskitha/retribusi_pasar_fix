<x-filament-panels::page>
    {{ $this->form }}


    <x-filament-widgets::widgets

        :columns="$this->getColumns()"

        :widgets="$this->getWidgets()"

    />
</x-filament-panels::page>
