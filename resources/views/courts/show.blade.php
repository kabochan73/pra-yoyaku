<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $court->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600">{{ $court->description }}</p>
                <p class="mt-2 text-lg font-semibold">¥{{ number_format($court->price_per_hour) }} / 時間</p>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('courts.index') }}"
                   class="text-blue-600 hover:underline">← コート一覧に戻る</a>

                @auth
                    <a href="{{ route('reservations.create', $court) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        予約する
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        ログインして予約する
                    </a>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
