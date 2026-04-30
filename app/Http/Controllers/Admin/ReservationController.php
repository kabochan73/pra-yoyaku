<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'court'])
            ->orderBy('start_at')
            ->get();

        return view('admin.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $court = Court::first();
        return view('admin.reservations.create', compact('court'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'date'       => 'required|date|after_or_equal:today',
            'start_hour' => 'required|integer|min:9|max:20',
            'duration'   => 'required|integer|min:1|max:3',
        ]);

        $court = Court::first();
        $start = Carbon::parse($request->date)->setHour((int) $request->start_hour)->setMinute(0)->setSecond(0);
        $end = $start->copy()->addHours((int) $request->duration);

        if ($end->hour > 21) {
            return back()->withErrors(['duration' => '終了時間が21:00を超えるため予約できません。'])->withInput();
        }

        $conflict = Reservation::where('court_id', $court->id)
            ->where('status', 'confirmed')
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'その時間帯はすでに予約が入っています。'])->withInput();
        }

        Reservation::create([
            'user_id'    => null,
            'court_id'   => $court->id,
            'guest_name' => $request->guest_name,
            'start_at'   => $start,
            'end_at'     => $end,
            'status'     => 'confirmed',
        ]);

        return redirect()->route('admin.reservations.index')->with('success', '予約を登録しました。');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('admin.reservations.index')->with('success', '予約をキャンセルしました。');
    }
}
