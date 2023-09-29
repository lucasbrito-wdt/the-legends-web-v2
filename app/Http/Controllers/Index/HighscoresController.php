<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otserver\Highscores;

class HighscoresController extends Controller
{
    public function index($list = 'experience', $world = 0, $vocation = '')
    {
        switch ($list) {
            case "fist":
                $id = Highscores::SKILL_FIST;
                $list_name = 'Fist Fighting';
                break;
            case "club":
                $id = Highscores::SKILL_CLUB;
                $list_name = 'Club Fighting';
                break;
            case "sword":
                $id = Highscores::SKILL_SWORD;
                $list_name = 'Sword Fighting';
                break;
            case "axe":
                $id = Highscores::SKILL_AXE;
                $list_name = 'Axe Fighting';
                break;
            case "distance":
                $id = Highscores::SKILL_DISTANCE;
                $list_name = 'Distance Fighting';
                break;
            case "shield":
                $id = Highscores::SKILL_SHIELD;
                $list_name = 'Shielding';
                break;
            case "fishing":
                $id = Highscores::SKILL_FISHING;
                $list_name = 'Fishing';
                break;
            case "magic":
                $id = Highscores::SKILL__MAGLEVEL;
                $list_name = 'Magic';
                break;
            case "experience":
                $id = Highscores::SKILL__LEVEL;
                $list_name = 'Experience';
                break;
            default:
                $id = Highscores::SKILL__LEVEL;
                $list_name = 'Experience';
                break;
        }

        $skills = new Highscores($id, $world);

        return view('index.highscores.index')->with([
            'world' => $world,
            'list' => $list,
            'vocation' => $vocation,
            'skills' => $skills
        ]);
    }
}
