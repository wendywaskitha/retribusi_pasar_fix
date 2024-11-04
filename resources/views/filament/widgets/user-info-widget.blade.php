<x-filament-widgets::widget>
    {{-- <x-filament::section> --}}
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-xl font-bold text-gray-800">User  Information</h2>
            <div class="mt-4">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 00-8 8 8 8 0 0016 0 8 8 0 00-8-8zm0 14a6 6 0 01-6-6 6 6 0 0112 0 6 6 0 01-6 6z"/><path d="M10 4a6 6 0 00-6 6 6 6 0 0012 0 6 6 0 00-6-6zm0 10a4 4 0 01-4-4 4 4 0 018 0 4 4 0 01-4 4z"/></svg>
                    <span class="font-semibold text-gray-700">Name:</span>
                    <span class="ml-2 text-gray-600">{{ $this->getUserInfo()['name'] }}</span>
                </div>
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 00-8 8 8 8 0 0016 0 8 8 0 00-8-8zm0 14a6 6 0 01-6-6 6 6 0 0112 0 6 6 0 01-6 6z"/><path d="M10 4a6 6 0 00-6 6 6 6 0 0012 0 6 6 0 00-6-6zm0 10a4 4 0 01-4-4 4 4 0 018 0 4 4 0 01-4 4z"/></svg>
                    <span class="font-semibold text-gray-700">Role:</span>
                    <span class="ml-2 text-gray-600">{{ $this->getUserInfo()['role'] }}</span>
                </div>
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 00-8 8 8 8 0 0016 0 8 8 0 00-8-8zm0 14a6 6 0 01-6-6 6 6 0 0112 0 6 6 0 01-6 6z"/><path d="M10 4a6 6 0 00-6 6 6 6 0 0012 0 6 6 0 00-6-6zm0 10a4 4 0 01-4-4 4 4 0 018 0 4 4 0 01-4 4z"/></svg>
                    <span class="font-semibold text-gray-700">Assigned Pasars:</span>
                    <span class="ml-2 text-gray-600">{{ implode(', ', $this->getUserInfo()['pasars']) }}</span>
                </div>
            </div>
        </div>
    {{-- </x-filament::section> --}}
</x-filament-widgets::widget>
