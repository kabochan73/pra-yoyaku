<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($reservations as $reservation)
                <div class="bg-white shadow-sm sm:rounded-lg mb-4 p-6 flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $reservation->court->name }}</p>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ $reservation->start_at->format('Y年m月d日 H:i') }}
                            〜
                            {{ $reservation->end_at->format('H:i') }}
                        </p>
                        <span class="inline-block mt-2 px-2 py-0.5 text-xs rounded-full
                            {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                            {{ $reservation->status === 'confirmed' ? '予約済み' : 'キャンセル済み' }}
                        </span>
                    </div>

                    @if ($reservation->status === 'confirmed')
                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST"
                              onsubmit="return confirm('予約をキャンセルしますか？')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                キャンセル
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    予約はまだありません。
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
