<?php
/*
 * @Author: Lucas Brito
 * @Data: 2021-01-08 10:24:01
 * @Github: https://github.com/luquinhasbrito/library-otserver
 * @Último Editor: Lucas Brito
 * @Última Hora da Edição: 2021-01-29 10:45:06
 * @Caminho do Arquivo: \TheLegends\app\Http\Controllers\Index\WhoisOnlineController.php
 * @Descrição:
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otserver\Account;
use Otserver\DatabaseList;
use Otserver\SQL_Field;
use Otserver\SQL_Filter;
use Otserver\SQL_Order;
use Otserver\Website;

class WhoisOnlineController extends Controller
{
    public function index(Request $request, $world = 0, $order = 'level', $orderDirection = 'desc', $orderAlphabetic = '')
    {
        $view = "index.whoisonline.index";

        $acc = new Account();

        $players = new DatabaseList();
        $players->setClass(\Otserver\Player::class);
        $filterOnline = new SQL_Filter(new SQL_Field('online'), SQL_FILTER::EQUAL, 1);
        $filterWorld = new SQL_Filter(new SQL_Field('world_id'), SQL_FILTER::EQUAL, $world);
        $filter = new SQL_Filter($filterOnline, SQL_Filter::CRITERIUM_AND, $filterWorld);
        foreach (config('otserver.site.groups_hidden') as $group)
            $filter = new SQL_Filter(new SQL_Filter(new SQL_Field('group_id'), SQL_Filter::NOT_EQUAL, $group), SQL_Filter::CRITERIUM_AND, $filter);
        foreach (config('otserver.site.accounts_hidden') as $account)
            $filter = new SQL_Filter(new SQL_Filter(new SQL_Field('account_id'), SQL_Filter::NOT_EQUAL, $account), SQL_Filter::CRITERIUM_AND, $filter);
        $players->setFilter($filter);
        $players->addOrder(new SQL_Order(new SQL_Field($order), $orderDirection));
        if (!empty($orderAlphabetic))
            $players->setSubstr("(`name`,1,1) = '{$orderAlphabetic}'");

        $record = Website::getDBHandle()->query('SELECT MAX(record) as r,MAX(timestamp) as t FROM server_record WHERE world_id = 0')->fetch();

        return view($view, [
            'players' => $players,
            'record' => $record,
            'world' => $world,
            'order' => $order,
            'orderDirection' => $orderDirection,
            'orderAlphabetic' => $orderAlphabetic
        ]);
    }
}
