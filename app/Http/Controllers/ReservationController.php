<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    public function create(Court $court)
    {
        return view('reservations.create', compact('court'));
    }

    public function store(Request $request, Court $court)
    {
        $request->validate([
            'start_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:3',
        ]);

        $start = Carbon::parse($request->start_at);
        $end = $start->copy()->addHours((int) $request->duration);

        // 予約ルール：同じコートで時間が重複していないか確認
        $conflict = Reservation::where('court_id', $court->id)
            ->where('status', 'confirmed')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_at', [$start, $end->copy()->subSecond()])
                      ->orWhereBetween('end_at', [$start->copy()->addSecond(), $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_at', '<=', $start)
                            ->where('end_at', '>=', $end);
                      });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['start_at' => 'その時間帯はすでに予約が入っています。'])->withInput();
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'court_id' => $court->id,
            'start_at' => $start,
            'end_at' => $end,
            'status' => 'confirmed',
        ]);

        return redirect()->route('mypage')->with('success', '予約が完了しました。');
    }

    public function mypage()
    {
        $reservations = auth()->user()->reservations()
            ->with('court')
            ->orderBy('start_at')
            ->get();

        return view('reservations.mypage', compact('reservations'));
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('mypage')->with('success', '予約をキャンセルしました。');
    }
}
