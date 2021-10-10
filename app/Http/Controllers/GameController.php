<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinGameRequest;
use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index()
    {
        return Inertia::render('Game/Create', [
            'user' => auth()->user(),
        ]);
    }

    public function Play(JoinGameRequest $request)
    {
        return Inertia::render('Game/Play', [
            'uuid' => $request->get('uuid')
        ]);
    }
}
