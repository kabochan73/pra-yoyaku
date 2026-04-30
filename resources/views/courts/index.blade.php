<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $court->name }}
        </h2>
    </x-slot>

    {{-- ヒーローセクション：画像 + カレンダーオーバーレイ --}}
    <div class="relative w-full" style="height: 700px;">
        <img src="{{ asset('images/court.jpg') }}" alt="{{ $court->name }}"
             class="absolute inset-0 w-full h-full object-cover">

        {{-- 右半分にカレンダーを重ねる --}}
        <div class="absolute top-0 right-0 h-full w-1/2 bg-white/90 overflow-y-auto p-4">

            {{-- 週ナビゲーション --}}
            <div class="flex items-center justify-between mb-2">
                <a href="{{ route('courts.index', ['week' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}"
                   class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200 text-xs">＜</a>
                <span class="text-xs font-semibold text-gray-800">
                    {{ $startOfWeek->format('n/j') }} 〜 {{ $startOfWeek->copy()->addDays(6)->format('n/j') }}
                </span>
                <a href="{{ route('courts.index', ['week' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}"
                   class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200 text-xs">＞</a>
            </div>

            @php
                $dowJa = ['月', '火', '水', '木', '金', '土', '日'];
                $hours = range(9, 20);
                $days = collect();
                for ($i = 0; $i < 7; $i++) {
                    $days->push($startOfWeek->copy()->addDays($i));
                }
            @endphp

            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr>
                        <th class="w-8 border border-gray-200 bg-gray-50 py-1 text-center text-gray-400"></th>
                        @foreach ($days as $i => $day)
                            <th class="border border-gray-200 py-1 text-center
                                {{ $day->isToday() ? 'bg-blue-50 text-blue-700 font-bold' : 'bg-gray-50 text-gray-600' }}">
                                <div>{{ $dowJa[$i] }}</div>
                                <div>{{ $day->format('n/j') }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hours as $hour)
                        <tr>
                            <td class="border border-gray-200 bg-gray-50 text-center text-gray-400 py-1">
                                {{ $hour }}
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
                                    'border border-gray-200 text-center py-1',
                                    'bg-red-100 text-red-500' => $reserved,
                                    'bg-gray-50 text-gray-300' => $isPast && !$reserved,
                                    'bg-white text-green-500' => !$reserved && !$isPast,
                                ])>
                                    @if ($reserved)
                                        <div>✕</div>
                                        @if (auth()->check() && auth()->user()->is_admin)
                                            <div class="truncate text-gray-400" style="font-size:9px">{{ $reserved->name }}</div>
                                        @endif
                                    @elseif (!$isPast)
                                        ○
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 凡例 --}}
            <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                <span><span class="text-green-500">○</span> 空き</span>
                <span><span class="text-red-500">✕</span> 予約済み</span>
            </div>
        </div>

        {{-- 左半分：コート情報 --}}
        <div class="absolute top-0 left-0 h-full w-1/2 flex flex-col justify-end p-8">
            <div class="bg-black/40 rounded-lg p-4 text-white">
                <h2 class="text-2xl font-bold">{{ $court->name }}</h2>
                <p class="text-sm mt-1 text-gray-200">{{ $court->description }}</p>
                <p class="mt-2 font-semibold">¥{{ number_format($court->price_per_hour) }} / 時間</p>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

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
