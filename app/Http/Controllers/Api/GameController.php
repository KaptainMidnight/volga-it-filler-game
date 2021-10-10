<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StepGameRequest;
use App\Models\Game;
use App\Traits\GameTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GameController extends Controller
{
    use GameTrait;

    public function create(Request $request)
    {
        $game = new Game();
        $game->uuid = $request->post('uuid');
        $game->user_id = $request->post('user_id');

        $field = [];
        for ($y = 0; $y < (500 * 2 - 1); $y++) {
            $field[$y] = [];
            for ($x = 0; $x < ($y % 2 == 0 ? 500 : (500 - 1)); $x++) {
                $field[$y][$x] = [
                    "color" => $this->colors[rand(0, count($this->colors) - 1)],
                    "user_id" => 0
                ];
            }
        }

        $field = $this->fill($field, 0, 500 * 2 - 2, $this->colors[0]);
        $field = $this->assingColorsPlayer($field, 0, 500 * 2 - 2, 1);

        $field = $this->fill($field, 500 - 1, 0, $this->colors[1]);
        $field = $this->assingColorsPlayer($field, 500 - 1, 0, 2);

        $game->data = $this->fieldToString($field);

        $game->save();

        return response()->json(['id' => $game->uuid], 201);
    }

    public function show($id)
    {
        if (!Str::isUuid($id)) {
            return response()->json([
                'message' => 'ID игры указан не верно'
            ]);
        }

        $game = Game::where('uuid', $id)->first();

        if (!$game) {
            return response()->json([
                'message' => 'Игра не найдена'
            ]);
        }

        $field = $this->stringToField($game->data, $game->width);
        $output = $this->fieldToOutput($field);

        $out = [
            'id' => $game->id,
            'field' => [
                'width' => $game->width,
                'height' => $game->height,
                'cells' => $output
            ],
            'players' => [
                [
                    'id' => 1,
                    'color' => $field[$game->height * 2 - 2][0]["color"],
                ],
                [
                    'id' => 2,
                    'color' => $field[0][$game->width - 1]["color"],
                ]
            ],
            'current_player' => auth()->id(),
            'winner' => $game->winner,
        ];

        return response()->json($out);
    }

    public function step(StepGameRequest $request, $gameId)
    {
        $userId = $request->input("user_id");
        $color = $request->input("color");

        if (!in_array($color, $this->colors)) {
            return response(null, 400);
        }

        if (!Str::isUuid($gameId)) {
            return response(null, 400);
        }

        $game = Game::where('uuid', $gameId)->first();
        if (!$game) {
            return response(null, 404);
        }

        if ($game->current_player != $userId) {
            return response(null, 403);
        }

        if ($game->winner != 0) {
            return response(null, 403);
        }

        $field = $this->stringToField($game->data, $game->width);
        $lockedColors = [$field[$game->height * 2 - 2][0]["color"], $field[0][$game->width - 1]["color"]];
        if (in_array($color, $lockedColors)) {
            return response(null, 409);
        }

        if ($userId == 1) {
            $field = $this->fill($field, 0, $game->height * 2 - 2, $color);
            $field = $this->assingColorsPlayer($field, 0, $game->height * 2 - 2, 1);
        } else {
            $field = $this->fill($field, $game->width - 1, 0, $color);
            $field = $this->assingColorsPlayer($field, $game->width - 1, 0, 2);
        }

        $game->data = $this->fieldToString($field);
        $game->winner = $this->getWinner($field);
        $game->current_player = ($game->winner == 0) ? ($userId % 2 + 1) : 0;
        $game->save();

        return response(null, 201);
    }
}
