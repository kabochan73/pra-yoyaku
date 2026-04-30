<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $court->name }}
        </h2>
    </x-slot>

    {{-- コート画像（横幅いっぱい） --}}
    <div class="w-full overflow-hidden shadow-sm mb-6">
        <img src="{{ asset('images/court.jpg') }}" alt="{{ $court->name }}"
             class="w-full h-72 object-cover">
    </div>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- コート情報 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <p class="text-gray-600">{{ $court->description }}</p>
                <p class="mt-2 text-lg font-semibold">¥{{ number_format($court->price_per_hour) }} / 時間</p>
            </div>

            {{-- 週タイムライン --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 overflow-x-auto">

                {{-- 週ナビゲーション --}}
                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('courts.index', ['week' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}"
                       class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">＜ 前の週</a>
                    <h3 class="font-semibold text-gray-800">
                        {{ $startOfWeek->format('Y年n月j日') }} 〜 {{ $startOfWeek->copy()->addDays(6)->format('n月j日') }}
                    </h3>
                    <a href="{{ route('courts.index', ['week' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}"
                       class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">次の週 ＞</a>
                </div>

                @php
                    $dowJa = ['月', '火', '水', '木', '金', '土', '日'];
                    $hours = range(9, 20);
                    $days = collect();
                    for ($i = 0; $i < 7; $i++) {
                        $days->push($startOfWeek->copy()->addDays($i));
                    }
                @endphp

                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr>
                            <th class="w-14 border border-gray-200 bg-gray-50 py-2 text-center text-gray-500 text-xs">時間</th>
                            @foreach ($days as $i => $day)
                                <th class="border border-gray-200 py-2 text-center
                                    {{ $day->isToday() ? 'bg-blue-50 text-blue-700' : 'bg-gray-50 text-gray-700' }}">
                                    <div>{{ $dowJa[$i] }}</div>
                                    <div class="text-xs {{ $day->isToday() ? 'font-bold' : 'font-normal' }}">
                                        {{ $day->format('n/j') }}
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hours as $hour)
                            <tr>
                                <td class="border border-gray-200 bg-gray-50 text-center text-xs text-gray-500 py-2">
                                    {{ sprintf('%02d', $hour) }}:00
                                </td>
                                @foreach ($days as $day)
                                    @php
                                        $slotStart = $day->copy()->setHour($hour)->setMinute(0)->setSecond(0);
                                        $slotEnd = $slotStart->copy()->addHour();
                                        $reserved = $reservations->first(
                                            fn($r) => $r->start_at < $slotEnd && $r->end_at > $slotStart
                                        );
                                        $isPast = $slotEnd->isPast();
                                    @endphp
                                    <td @class([
                                        'border border-gray-200 text-center text-xs py-2',
                                        'bg-red-100 text-red-700' => $reserved,
                                        'bg-gray-50 text-gray-300' => $isPast && !$reserved,
                                        'bg-white' => !$reserved && !$isPast,
                                    ])>
                                        @if ($reserved)
                                            <div>予約済</div>
                                            @if (auth()->check() && auth()->user()->is_admin)
                                                <div class="truncate text-gray-500">{{ $reserved->name }}</div>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- 凡例 --}}
                <div class="mt-4 flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <span class="inline-block w-3 h-3 bg-red-100 rounded"></span> 予約済み
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="inline-block w-3 h-3 bg-gray-50 border border-gray-200 rounded"></span> 過去
                    </span>
                </div>
            </div>

            {{-- 予約ボタン --}}
            <div class="flex justify-end">
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
