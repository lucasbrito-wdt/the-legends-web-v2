<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otserver\Player;
use Otserver\Website;
use Otserver\PlayerDeath;

class CharactersController extends Controller
{
    public function redirectWithParams(Request $request)
    {
        return redirect()->route('characters.index', ['name' => urlencode($request->name)]);
    }

    public function index($name = "")
    {
        if (empty($name)) {
            return view('index.characters.search');
        }

        $player = new Player(urldecode($name), Player::LOADTYPE_NAME);
        if (!$player->isLoaded())
            return view('errors.custom', ["message" => "Personagem <b>" . htmlspecialchars($name) . "</b> não existe."]);

        $account = $player->getAccount();
        $SQL = Website::getDBHandle();

        $skull = "";
        if ($player->getSkull() == 4)
            $skull = asset('images/skulls/redskull.gif');
        else if ($player->getSkull() == 5)
            $skull = asset('images/skulls/blackskull.gif');

        $hpPercent = max(0, min(100, $player->getHealth() / max(1, $player->getHealthMax()) * 100));
        $hpPercent = str_replace(',', '.', $hpPercent);

        $manaPercent = max(0, min(100, $player->getMana() / max(1, $player->getManaMax()) * 100));
        $manaPercent = str_replace(',', '.', $manaPercent);

        $itemsList = $player->getItems();
        $list = array('2', '1', '3', '6', '4', '5', '9', '7', '10', '8');

        $rank_of_player = $player->getRank();

        $house = Website::getDBHandle()->query('SELECT `houses`.`name`, `houses`.`town`, `houses`.`lastwarning` FROM `houses` WHERE `houses`.`world_id` = ' . $player->getWorld() . ' AND `houses`.`owner` = ' . $player->getId() . ';')->fetchAll();

        $player_deaths = $player->getDeaths();

        return view('index.characters.index', [
            'account' => $account,
            'player' => $player,
            'skull' => $skull,
            'hpPercent' => $hpPercent,
            'manaPercent' => $manaPercent,
            'itemsList' => $itemsList,
            'list' => $list,
            'rank_of_player' => $rank_of_player,
            'house' => $house,
            'player_deaths' => $player_deaths
        ]);
    }
}
