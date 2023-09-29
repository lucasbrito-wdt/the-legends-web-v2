<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Otserver\Website;

class SpellsController extends Controller
{
    public function index(Request $request, $voc = "All", $group = "All", $type = "All", $premium = "All")
    {
        $spells = Website::getSpells();
        $vocations = Website::getVocations();

        $groupsCallback = function ($groups, $item) {
            if (!is_array($groups)) {
                $groups = [];
            }
            if (!in_array((string)$item['group'], $groups, true))
                if (!empty($item['group']))
                    $groups[] = $item['group'];

            return $groups;
        };

        $typesCallback = function ($types, $item) {
            if (!is_array($types)) {
                $types = [];
            }
            if (!in_array((string)$item['type'], $types, true))
                if (!empty($item['type']))
                    $types[] = $item['type'];
            return $types;
        };

        $vocationsCallback = function ($vocs, $item) {
            if (!is_array($vocs)) {
                $vocs = [];
            }
            if (!in_array((string)$item->getName(), $vocs, true))
                if (!empty($item->getName()))
                    $vocs[] = $item->getName();
            return $vocs;
        };

        $groups = array_reduce($spells->spells, $groupsCallback);
        $types = array_reduce($spells->spells, $typesCallback);
        $vocs = array_reduce($vocations->vocations, $vocationsCallback);

        return view('index.spells.index', [
            'spells' => $spells,
            'groups' => $groups,
            'types' => $types,
            'vocs' => $vocs
        ]);
    }
}
