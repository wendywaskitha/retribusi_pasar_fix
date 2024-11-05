<x-filament-widgets::widget>
    <div class="relative p-2 overflow-hidden transition-all duration-300 bg-white shadow rounded-xl hover:shadow-lg sm:p-4 lg:p-6 user-info-card">
        <div wire:loading.delay.shorter class="absolute inset-0 z-10 flex items-center justify-center bg-white/50 rounded-xl">
            <svg class="w-8 h-8 text-indigo-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div class="flex flex-col items-center p-2 space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-20 h-20 transition-transform duration-300 transform rounded-full shadow-lg user-info-gradient hover:scale-105">
                    <span class="text-3xl font-bold text-white">
                        {{ strtoupper(substr($this->getUserInfo()['name'], 0, 1)) }}
                    </span>
                </div>
            </div>
            <div class="flex-1 min-w-0 text-center sm:text-left">
                <p class="text-lg font-medium text-gray-900 truncate">
                    {{ $this->getUserInfo()['name'] }}
                </p>
                <p class="text-sm text-gray-500 truncate">
                    {{ $this->getUserInfo()['email'] }}
                </p>
                <p class="text-sm font-medium text-indigo-600">
                    {{ ucfirst($this->getUserInfo()['role']) }}
                </p>
            </div>
        </div>

        <div class="mt-4 border-t border-gray-200">
            <div class="px-4 py-3">
                <h3 class="flex items-center text-sm font-medium text-gray-900">
                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Assigned Markets
                </h3>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($this->getUserInfo()['pasars'] as $pasar)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-indigo-800 transition-all duration-300 bg-indigo-100 rounded-full cursor-default hover:bg-indigo-200 hover:shadow-sm market-tag">
                            {{ $pasar }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="px-4 py-2 mt-2 text-xs text-gray-500 bg-gray-50 rounded-b-xl">
            <div class="flex items-center justify-between">
                <span class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Last seen: {{ $this->getUserInfo()['last_login'] }}
                </span>
                <span class="flex items-center text-green-500">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Online
                </span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
