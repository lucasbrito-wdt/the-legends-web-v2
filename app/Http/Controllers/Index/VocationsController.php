<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Website;

class VocationsController extends Controller
{
    public function index()
    {
        $vocations = Website::getVocations()->data();
        $linhagemSum = (int)(count($vocations) / 4) - 1;

        $s = 1;
        $d = 2;
        $p = 3;
        $k = 4;

        $linSoc = [1 => "Sorcerer"];
        $linEd = [2 => "Druid"];
        $linPala = [3 => "Paladin"];
        $linKina = [4 => "Knight"];

        for ($i = 1; $i <= $linhagemSum; $i++) {

            $s += 4;
            $d += 4;
            $p += 4;
            $k += 4;

            $linSoc[$s] = Website::getVocationName($s);
            $linEd[$d] = Website::getVocationName($d);
            $linPala[$p] = Website::getVocationName($p);
            $linKina[$k] = Website::getVocationName($k);
        }

        return view('index.vocations.index', [
            'linSoc' => $linSoc,
            'linEd' => $linEd,
            'linPala' => $linPala,
            'linKina' => $linKina,
        ]);
    }

    public function show($vocation)
    {
        $vocations = Website::getVocations()->data();
        $linhagemSum = (int)(count($vocations) / 4) - 1;

        $s = 1;
        $d = 2;
        $p = 3;
        $k = 4;

        $namesSoc = [1 => "Sorcerer"];
        $namesEd = [2 => "Druid"];
        $namesPala = [3 => "Paladin"];
        $namesKina = [4 => "Knight"];

        for ($i = 1; $i <= $linhagemSum; $i++) {

            $s += 4;
            $d += 4;
            $p += 4;
            $k += 4;

            $namesSoc[$s] = Website::getVocationName($s);
            $namesEd[$d] = Website::getVocationName($d);
            $namesPala[$p] = Website::getVocationName($p);
            $namesKina[$k] = Website::getVocationName($k);
        }

        $infoSoc = "Como druids, sorcerers focam o uso de magia. Similar aos seus irmãos mais pacífico, suas habilidades de armas são muito limitadas, no entanto, os sorcerers têm um grande potencial. Suas magias são as mais mortais de todas as vocações, ao contrário de druids que se concentram no lado benevolentes de magia, sorcerers se concentram no seu lado escuro, destrutivo, suas magias podem ser realmente devastadoras nos níveis mais elevados. Um sorcerer experiente pode derrubar o mais poderoso inimigo com um piscar de olho, por isso, os sorcerers são uma adição bem-vinda a qualquer equipe de caça a monstros poderosos. Se é pura magia e poder de fogo que você está procurando, você deve ser um sorcerer.";
        $infoEd = "Druids são usuários de pura magia. Como magos, eles são fracos de construção, as habilidades de sua arma são bastante limitados. Druids têm um pequeno número de magias ofensivas à seu uso, porém consiste de diversas magias de buff e cura. Druids são os melhores curandeiros do Dragon Souls, sua capacidade de curar e buffar os outros o torna muito popular em locais de caça em equipe e guerras. Para druids existe uma grande vantagem quando se trata de fazer amigos. Se você preferir usar magias poderosas em vez de força bruta, se você também é um jogador de equipe que gosta de cooperar com os outros, você deve realmente escolher um druid.";
        $infoPala = "Paladin também são lutadores bastante talentosos, embora eles não sejam tão resistentes como knights. Porém sua capacidade de luta é à distância. Archers podem aprender a lidar com qualquer arma de distância com precisão mortal. Um Archer que consegue manter-se longe de seus adversários no campo de batalha pode derrubar qualquer inimigo. Eles também são usuários de magias habilidosas e uma excelente esquiva. Apesar de sua habilidade mágica não ser comparada com a de pura magia de usuários como druids ou sorcerers, Archer têm acesso a muitas magias adicionais. Se você está procurando um bom lutador, que também pode manipular muito bem a magia, o Archer deve ser sua escolha.";
        $infoKina = "Knights são os guerreiros mais resistentes no Dragon Souls. Eles são fortes, resistentes e que pode manejar qualquer arma branca com uma eficiência assustadora. Em combate, eles são encontrados sempre na linha da frente. Devido à sua incrível capacidade de resistência e seu uso hábil de escudos os knights experientes são quase impossíveis de superar, mesmo que a batalha esteja perdida, é geralmente os knights que são os últimos a cair. No entanto, knights são os melhores blocadores do Dragon Souls e por isso são bem-vindo em cada grupo de caça. Se você está procurando uma vocação que é fácil de jogar e subir de nível, o knight é o que você está procurando.";

        $linCallback = function ($array, $item) use ($vocation, $namesSoc, $namesEd, $namesPala, $namesKina) {
            switch ($vocation) {
                case "sorcerer":
                    foreach ($namesSoc as $key => $val) {
                        if ($item->getId() == $key) {
                            $array[] = $item;
                        }
                    }
                    break;
                case "druid":
                    foreach ($namesEd as $key => $val) {
                        if ($item->getId() == $key) {
                            $array[] = $item;
                        }
                    }
                    break;
                case "paladin":
                    foreach ($namesPala as $key => $val) {
                        if ($item->getId() == $key) {
                            $array[] = $item;
                        }
                    }
                    break;
                case "knight":
                    foreach ($namesKina as $key => $val) {
                        if ($item->getId() == $key) {
                            $array[] = $item;
                        }
                    }
                    break;
            }

            return $array;
        };
        $linVocs = array_reduce($vocations, $linCallback);

        switch ($vocation) {
            case "sorcerer":
                return view('index.vocations.show', [
                    'lin' => $namesSoc,
                    'vocation' => $vocation,
                    'info' => $infoSoc,
                    'linVocs' => $linVocs
                ]);
                break;
            case "druid":
                return view('index.vocations.show', [
                    'lin' => $namesEd,
                    'vocation' => $vocation,
                    'info' => $infoEd,
                    'linVocs' => $linVocs
                ]);
                break;
            case "paladin":
                return view('index.vocations.show', [
                    'lin' => $namesPala,
                    'vocation' => $vocation,
                    'info' => $infoPala,
                    'linVocs' => $linVocs
                ]);
                break;
            case "knight":
                return view('index.vocations.show', [
                    'lin' => $namesKina,
                    'vocation' => $vocation,
                    'info' => $infoKina,
                    'linVocs' => $linVocs
                ]);
                break;
            default:
                abort(404);
                break;
        }
    }
}
