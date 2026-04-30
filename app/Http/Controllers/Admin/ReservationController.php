<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'court'])
            ->orderBy('start_at')
            ->get();

        return view('admin.reservations.index', compact('reservations'));
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('admin.reservations.index')->with('success', '予約をキャンセルしました。');
    }
}
