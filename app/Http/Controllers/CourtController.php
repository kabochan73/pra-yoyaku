<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CourtController extends Controller
{
    public function index(Request $request)
    {
        $court = Court::first();

        $startOfWeek = Carbon::parse(
            $request->query('week', Carbon::today()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'))
        )->startOfWeek(Carbon::MONDAY);

        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);

        $reservations = $court->reservations()
            ->where('status', 'confirmed')
            ->where('start_at', '<', $endOfWeek)
            ->where('end_at', '>', $startOfWeek)
            ->orderBy('start_at')
            ->get();

        return view('courts.index', compact('court', 'reservations', 'startOfWeek'));
    }

    public function create()
    {
        return view('courts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|integer|min:0',
        ]);

        Court::create($request->only('name', 'description', 'price_per_hour'));

        return redirect()->route('courts.index')->with('success', 'コートを登録しました。');
    }

    public function show(Request $request, Court $court)
    {
        $startOfWeek = Carbon::parse(
            $request->query('week', Carbon::today()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'))
        )->startOfWeek(Carbon::MONDAY);

        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);

        $reservations = $court->reservations()
            ->where('status', 'confirmed')
            ->where('start_at', '<', $endOfWeek)
            ->where('end_at', '>', $startOfWeek)
            ->orderBy('start_at')
            ->get();

        return view('courts.show', compact('court', 'reservations', 'startOfWeek'));
    }

    public function destroy(Court $court)
    {
        $court->delete();
        return redirect()->route('courts.index')->with('success', 'コートを削除しました。');
    }
}
