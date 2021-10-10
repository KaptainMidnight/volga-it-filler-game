<?php

namespace App\Traits;

use App\Models\Color;

trait GameTrait
{
    private $colors;

    public function __construct()
    {
        $this->colors = Color::all()->pluck('name')->toArray();
    }

    public function fieldToString($field)
    {
        $string = "";
        foreach ($field as $y) {
            foreach ($y as $x) {
                $color_id = array_search($x["color"], $this->colors) % 10;
                $string .= $color_id . $x["user_id"] % 10;
            }
        }
        return $string;
    }

    public function stringToField($string, $width)
    {
        $x = -1;
        $y = 0;

        $field = [];
        foreach (str_split($string) as $pos => $value) {
            $value = intval($value);
            $var = $pos % 2;
            if ($var == 0) {
                $x++;
                if (($y % 2 == 0) ? ($x >= $width) : ($x >= ($width - 1))) {
                    $x = 0;
                    $y++;
                }
            }

            switch ($var) {
                case 0:
                    if (!array_key_exists($y, $field)) {
                        $field[$y] = [];
                    }

                    $field[$y][$x] = [
                        "color" => $this->colors[$value],
                        "user_id" => null,
                    ];
                    break;
                case 1:
                    $field[$y][$x]["user_id"] = $value;
                    break;
                default:
                    break;
            }
        }

        return $field;
    }

    public function fieldToOutput($field)
    {
        $output = [];
        foreach ($field as $y) {
            foreach ($y as $x) {
                array_push($output, $x);
            }
        }
        return $output;
    }

    public function recursiveWork($field, $x, $y, $worker, $data = [])
    {
        $checked = [];
        $toCheck = [];
        array_push($toCheck, [$x, $y]);

        while ($work = array_pop($toCheck)) {
            $x = $work[0];
            $y = $work[1];

            if (!(array_key_exists($y, $checked) && array_key_exists($x, $checked[$y]))) {
                if (array_key_exists($y, $field) && array_key_exists($x, $field[$y])) {
                    $newData = $this->$worker($field, $x, $y, $data);
                    $field = $newData[0];
                    $closedContinue = $newData[1];

                    if (!array_key_exists($y, $checked)) $checked[$y] = [];
                    $checked[$y][$x] = true;

                    if ($closedContinue) {
                        if ($y % 2 != 0) {
                            array_push($toCheck, [$x, $y - 1]);
                            array_push($toCheck, [$x + 1, $y - 1]);
                            array_push($toCheck, [$x, $y + 1]);
                            array_push($toCheck, [$x + 1, $y + 1]);
                        } else {
                            array_push($toCheck, [$x, $y - 1]);
                            array_push($toCheck, [$x - 1, $y - 1]);
                            array_push($toCheck, [$x, $y + 1]);
                            array_push($toCheck, [$x - 1, $y + 1]);
                        }
                    }
                }
            }
        }

        return $field;
    }

    public function assingColorsPlayerFunction($field, $x, $y, $data)
    {
        $closedContinue = false;
        if ($field[$y][$x]["color"] == $data["color"]) {
            if ($field[$y][$x]["user_id"] == 0) $field[$y][$x]["user_id"] = $data["player"];
            $closedContinue = true;
        }
        return [$field, $closedContinue];
    }

    public function assingColorsPlayer($field, $x, $y, $player)
    {
        $color = $field[$y][$x]["color"];
        $field = $this->recursiveWork($field, $x, $y, "assingColorsPlayerFunction", [
            "color" => $color,
            "player" => $player
        ]);
        return $field;
    }

    public function fillFunction($field, $x, $y, $data)
    {
        $closedContinue = false;
        if ($field[$y][$x]["color"] == $data["old_color"]) {
            $field[$y][$x]["color"] = $data["new_color"];
            $closedContinue = true;
        }
        return [$field, $closedContinue];
    }

    public function fill($field, $x, $y, $new_color)
    {
        $old_color = $field[$y][$x]["color"];
        $field = $this->recursiveWork($field, $x, $y, "fillFunction", [
            "old_color" => $old_color,
            "new_color" => $new_color
        ]);
        return $field;
    }

    public function getWinner($field)
    {
        $linearField = $this->fieldToOutput($field);
        $all = count($linearField);
        $p1 = 0;
        $p2 = 0;
        foreach ($this->fieldToOutput($field) as $cell) {
            switch ($cell["user_id"]) {
                case 1:
                    $p1++;
                    break;
                case 2:
                    $p2++;
                    break;
                default:
                    break;
            }
        }
        if (($p1 / $all) > 0.5) return 1;
        if (($p2 / $all) > 0.5) return 2;
        return 0;
    }
}
