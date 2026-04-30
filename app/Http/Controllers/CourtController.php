<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::all();
        return view('courts.index', compact('courts'));
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

    public function show(Court $court)
    {
        return view('courts.show', compact('court'));
    }

    public function destroy(Court $court)
    {
        $court->delete();
        return redirect()->route('courts.index')->with('success', 'コートを削除しました。');
    }
}
