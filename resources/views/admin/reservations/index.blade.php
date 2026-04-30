<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            管理者：全予約一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('admin.reservations.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    電話予約を登録する
                </a>
            </div>

            @forelse ($reservations as $reservation)
                <div class="bg-white shadow-sm sm:rounded-lg mb-4 p-6 flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $reservation->court->name }}</p>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ $reservation->start_at->format('Y年m月d日 H:i') }}
                            〜
                            {{ $reservation->end_at->format('H:i') }}
                        </p>
                        <p class="text-gray-500 text-sm">
                            お客様：{{ $reservation->name }}
                            @if ($reservation->guest_name)
                                <span class="ml-1 text-xs bg-yellow-100 text-yellow-700 px-1 rounded">電話予約</span>
                            @endif
                        </p>
                        <span class="inline-block mt-2 px-2 py-0.5 text-xs rounded-full
                            {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                            {{ $reservation->status === 'confirmed' ? '予約済み' : 'キャンセル済み' }}
                        </span>
                    </div>

                    @if ($reservation->status === 'confirmed')
                        <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST"
                              onsubmit="return confirm('この予約をキャンセルしますか？')">
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
