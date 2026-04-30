<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            コート一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($courts as $court)
                <div class="bg-white shadow-sm sm:rounded-lg mb-4 p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $court->name }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $court->description }}</p>
                        <p class="text-gray-800 mt-1">¥{{ number_format($court->price_per_hour) }} / 時間</p>
                    </div>
                    <a href="{{ route('courts.show', $court) }}"
                       class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">
                        詳細・予約
                    </a>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    登録されているコートはありません。
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
