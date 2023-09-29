<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otserver\DatabaseList;
use Otserver\SQL_Filter;
use Otserver\SQL_Field;
use Otserver\SQL_Order;
use Otserver\Website;

class HousesController extends Controller
{
    public function index(Request $request, $world = 0, $town = 1, $owner = 2, $order = 'name')
    {
        if ($request->has('town'))
            $town = (int)$request->input('town');
        if ($request->has('owner'))
            $owner = (int)$request->input('owner');
        if ($request->has('order'))
            $order = $request->input('order');

        $houses = new DatabaseList('House');
        $filterTown = new SQL_Filter(new SQL_Field('town'), SQL_Filter::EQUAL, $town);
        if ($owner == 0) {
            $filterOwner = new SQL_Filter(new SQL_Field('owner'), SQL_Filter::EQUAL, 0);
            $filter = new SQL_Filter($filterTown, SQL_Filter::CRITERIUM_AND, $filterOwner);
        } elseif ($owner == 1) {
            $filterOwner = new SQL_Filter(new SQL_Field('owner'), SQL_Filter::NOT_EQUAL, 0);
            $filter = new SQL_Filter($filterTown, SQL_Filter::CRITERIUM_AND, $filterOwner);
        } else {
            $filter = $filterTown;
        }
        $houses->setFilter($filter);

        if ($order == 'size')
            $houses->addOrder(new SQL_Order(new SQL_Field('size', 'houses'), SQL_Order::DESC));
        elseif ($order == 'name')
            $houses->addOrder(new SQL_Order(new SQL_Field('name', 'houses')));
        elseif ($order == 'price')
            $houses->addOrder(new SQL_Order(new SQL_Field('price', 'houses'), SQL_Order::DESC));

        $ownersIds = [];
        $owners = [];

        if (count($houses) > 0) {
            $SQL = Website::getDBHandle();

            foreach ($houses as $house) {
                if ($house->getOwner() != 0)
                    $ownersIds[] = $house->getOwner();
            }

            if (count($ownersIds) > 0) {
                $ownersList = new DatabaseList('Player');
                $ownersList->data = $SQL->query('SELECT * FROM ' . $SQL->tableName('players') . ' WHERE ' . $SQL->fieldName('id') . ' IN (' . implode(',', $ownersIds) . ');')->fetchAll();
                foreach ($ownersList as $item) {
                    $owners[$item->getID()] = $item;
                }
            }
        }

        if ($request->method() == "POST") {
            return redirect()->route('houses.index', [
                'world' => $world,
                'town' => $town,
                'owner' => $owner,
                'order' => $order,
            ])->with([
                'houses' => $houses,
                'owners' => $owners
            ]);
        }

        return view("index.houses.index", [
            'world' => $world,
            'town' => $town,
            'owner' => $owner,
            'order' => $order,
            'houses' => $houses,
            'owners' => $owners
        ]);
    }
}
