<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otserver\DatabaseList;
use Otserver\SQL_Filter;
use Otserver\SQL_Field;
use Otserver\SQL_Order;

class KillStatisticsController extends Controller
{
    public function index()
    {
        $deaths = new DatabaseList();
        $deaths->setClass('PlayerDeath');
        $deaths->setFilter(new SQL_Filter(new SQL_Field('id', 'players'), SQL_Filter::EQUAL, new SQL_Field('player_id', 'player_deaths')));
        $deaths->addOrder(new SQL_Order(new SQL_Field('date'), SQL_Order::DESC));
        $deaths->addExtraField(new SQL_Field('name', 'players'));
        $deaths->addExtraField(new SQL_Field('world_id', 'players'));
        $deaths->setLimit(20);
        $deaths->load();

        return view('index.killstatistics.index', [
            'deaths' => $deaths
        ]);
    }
}
