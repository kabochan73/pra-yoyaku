<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約する - {{ $court->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600 mb-1">{{ $court->description }}</p>
                <p class="font-semibold mb-6">¥{{ number_format($court->price_per_hour) }} / 時間</p>

                <form action="{{ route('reservations.store', $court) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">開始日時</label>
                        <input type="datetime-local" name="start_at" value="{{ old('start_at') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                        @error('start_at')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">利用時間</label>
                        <select name="duration"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <option value="1" {{ old('duration') == 1 ? 'selected' : '' }}>1時間</option>
                            <option value="2" {{ old('duration') == 2 ? 'selected' : '' }}>2時間</option>
                            <option value="3" {{ old('duration') == 3 ? 'selected' : '' }}>3時間</option>
                        </select>
                        @error('duration')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('courts.show', $court) }}"
                           class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">
                            キャンセル
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            予約する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
