<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otserver\Ban;
use Otserver\DatabaseList;
use Otserver\Visitor;
use Otserver\ShopOffer;
use Otserver\Functions;
use Otserver\ItemsList;
use Otserver\OtsComunication;
use Otserver\Player;
use Otserver\ShopHistoryItem;
use Otserver\ShopHistoryPacc;
use Otserver\Website;
use Otserver\SQL_Filter;
use Otserver\SQL_Field;
use Otserver\SQL_Order;
use Otserver\Item;

class ShopController extends Controller
{
    public function index($id = "")
    {
        $errorsView = false;
        $logged = Visitor::isLogged();
        $view = "index.shop.index";

        $shopOffer = new DatabaseList();
        $shopOffer->setClass(\Otserver\ShopOffer::class);
        $shopOffer->setFilter(new SQL_Filter(new SQL_Field('active'), SQL_Filter::EQUAL, true));
        $shopOffer->addOrder(new SQL_Order(new SQL_Field('category', 'z_shop_offer'), SQL_Order::DESC));

        if (!empty($id)) {
            $shopOffer->setFilter(new SQL_Filter(new SQL_Field('id'), SQL_Filter::EQUAL, (int)$id));
            $shopOffer->load();

            if (!$shopOffer->valid()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center mb-2">Oferta não encontrada!</p><a href="' . route('shop.index') . '" class="d-block mx-auto text-center sbutton-blue">Voltar a loja</a>');
            }
            return view($view, ['errorsView' => $errorsView, 'logged' => $logged, 'shopOffer' => $shopOffer]);
        } else {
            return view($view, ['errorsView' => $errorsView, 'logged' => $logged, 'shopOffer' => $shopOffer]);
        }

        $errorsView = false;
        return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors("Nenhuma oferta foi encontrada!");
    }

    public function selectplayer(Request $request, $buyId)
    {
        $errorsView = false;
        $logged = Visitor::isLogged();
        $account_logged = Visitor::getAccount();
        $view = "index.shop.selectplayer";

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('Você não está logado.');
        }

        $players_from_logged_acc = $account_logged->getPlayers(true);
        if (!count($players_from_logged_acc) > 0) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('Você não tem nenhum personagem em sua conta.');
        }

        if (empty($buyId)) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('Please <a href="/shop">select item</a> first.');
        } else {
            $buyOffer = new ShopOffer();
            $buyOffer->load((int)$buyId, ShopOffer::LOADTYPE_ID);
            return view($view, [
                'errorsView' => $errorsView,
                'logged' => $logged,
                'players_from_logged_acc' => $players_from_logged_acc,
                'buyOffer' => $buyOffer
            ]);
        }
    }

    public function confirmtransaction(Request $request)
    {
        $errorsView = false;
        $logged = Visitor::isLogged();
        $account_logged = Visitor::getAccount();
        $view = "index.shop.confirmtransaction";
        $SQL = Website::getDBHandle();

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('Você não está logado.');
        }

        if ($request->method() == "POST") {
            $buy_id = (int) request()->input('buy_id');
            $buy_name = urldecode(request()->input('buy_name'));
            $buy_from = request()->input('buy_from');

            if (empty($buy_from)) {
                $buy_from = 'Anonymous';
            }
            if (empty($buy_id)) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView])->withErrors('Por favor, <a href="' . route('shop.index') . '">selecione o item</a> primeiro.');
            } else {
                if (!Functions::check_name($buy_from)) {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView])->withErrors('Formato do nick ("do jogador") é inválido. Por favor, <a href="' . route('shop.selectplayer', ['buyId' => $buy_id]) . '">selecione outro nome</a> ou contato com o administrador.');
                } else {
                    $buyOffer = new ShopOffer();
                    $buyOffer->load((int)$buy_id, ShopOffer::LOADTYPE_ID);

                    if ($account_logged->getPremiumPoints() >= $buyOffer->getPoints()) {
                        if (Functions::check_name($buy_name)) {
                            $buy_player = new Player();
                            $buy_player->find($buy_name);

                            if ($buy_player->isLoaded()) {
                                $buy_player_account = $buy_player->getAccount();
                                if ($buyOffer->getOfferType() == ShopOffer::OFFERTYPE_ITEM) {
                                    $otsComunication = new OtsComunication();
                                    $otsComunication->setName($buy_player->getName());
                                    $otsComunication->setType('login');
                                    $otsComunication->setAction('give_item');
                                    $otsComunication->setParam1($buyOffer->getItemId1());
                                    $otsComunication->setParam2($buyOffer->getCount1());
                                    $otsComunication->setParam5('item');
                                    $otsComunication->setParam6($buyOffer->getOfferName());
                                    $otsComunication->setDeleteIt(1);
                                    $otsComunication->save();

                                    $shopHistoryItem = new ShopHistoryItem();
                                    $shopHistoryItem->setToName($buy_player->getName());
                                    $shopHistoryItem->setToAccount($buy_player_account->getId());
                                    $shopHistoryItem->setFromNick($buy_from);
                                    $shopHistoryItem->setFromAccount($account_logged->getId());
                                    $shopHistoryItem->setPrice($buyOffer->getPoints());
                                    $shopHistoryItem->setOfferId($buyOffer->getOfferName());
                                    $shopHistoryItem->setTransState('wait');
                                    $shopHistoryItem->setTransStart(time());
                                    $shopHistoryItem->setTransReal(0);
                                    $shopHistoryItem->save();

                                    $account_logged->setPremiumPoints($account_logged->getPremiumPoints() - $buyOffer->getPoints());
                                    $account_logged->save();

                                    $errorsView = true;
                                    return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<h1 class="text-center fw-bold">Item adicionado!</h1><p class="text-center"><b>' . $buyOffer->getOfferName() . '</b> adicionado ao jogador <b>' . htmlspecialchars($buy_player->getName()) . '</b> itens (ele obterá esses itens após relogar) para <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b> da sua conta.</p><p class="text-center">Agora você tem <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                } else if ($buyOffer->getOfferType() == ShopOffer::OFFERTYPE_CONTAINER) {
                                    $otsComunication = new OtsComunication();
                                    $otsComunication->setName($buy_player->getName());
                                    $otsComunication->setType('login');
                                    $otsComunication->setAction('give_item');
                                    $otsComunication->setParam1($buyOffer->getItemId1());
                                    $otsComunication->setParam2($buyOffer->getCount1());
                                    $otsComunication->setParam3($buyOffer->getItemId2());
                                    $otsComunication->setParam3($buyOffer->getCount2());
                                    $otsComunication->setParam5('container');
                                    $otsComunication->setParam6($buyOffer->getOfferName());
                                    $otsComunication->setDeleteIt(1);
                                    $otsComunication->save();

                                    $shopHistoryItem = new ShopHistoryItem();
                                    $shopHistoryItem->setToName($buy_player->getName());
                                    $shopHistoryItem->setToAccount($buy_player_account->getId());
                                    $shopHistoryItem->setFromNick($buy_from);
                                    $shopHistoryItem->setFromAccount($account_logged->getId());
                                    $shopHistoryItem->setPrice($buyOffer->getPoints());
                                    $shopHistoryItem->setOfferId($buyOffer->getOfferName());
                                    $shopHistoryItem->setTransState('wait');
                                    $shopHistoryItem->setTransStart(time());
                                    $shopHistoryItem->setTransReal(0);
                                    $shopHistoryItem->save();

                                    $account_logged->setPremiumPoints($account_logged->getPremiumPoints() - $buyOffer->getPoints());
                                    $account_logged->save();

                                    $errorsView = true;
                                    return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<h1 class="text-center fw-bold">Item adicionado!</h1><p class="text-center"><b>' . $buyOffer->getOfferName() . '</b> adicionado ao jogador <b>' . htmlspecialchars($buy_player->getName()) . '</b> itens (ele obterá esses itens após relogar) para <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b> da sua conta.</p><p class="text-center">Agora você tem <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                } else if ($buyOffer->getOfferType() == ShopOffer::OFFERTYPE_PACC) {
                                    $shopHistoryPacc = new ShopHistoryPacc();
                                    $shopHistoryPacc->setToName($buy_player->getName());
                                    $shopHistoryPacc->setToAccount($buy_player_account->getId());
                                    $shopHistoryPacc->setFromNick($buy_from);
                                    $shopHistoryPacc->setFromAccount($account_logged->getId());
                                    $shopHistoryPacc->setPrice($buyOffer->getPoints());
                                    $shopHistoryPacc->setPaccDays($buyOffer->getCount1());
                                    $shopHistoryPacc->setTransState('realized');
                                    $shopHistoryPacc->setTransStart(time());
                                    $shopHistoryPacc->setTransReal(time());
                                    $shopHistoryPacc->save();

                                    if ($buy_player_account->getPremDays() > 0)
                                        $buy_player_account->setPremDays($buy_player_account->getPremDays() + $buyOffer->getCount1() * 86400);
                                    else
                                        $buy_player_account->setPremDays(time() + $buyOffer->getCount1() * 86400);

                                    $account_logged->setPremiumPoints($account_logged->getPremiumPoints() - $buyOffer->getPoints());
                                    $account_logged->save();

                                    $errorsView = true;
                                    return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<h1 class="text-center fw-bold">Dias Premium adicionados!!</h1><p class="text-center"><b>' . $buyOffer->getCount1() . '</b> dias de premium adicionados à conta do jogador <b>' . htmlspecialchars($buy_player->getName()) . '</b>.</p><p class="text-center">Agora você tem <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                } else if ($buyOffer->getOfferType() == ShopOffer::OFFERTYPE_CHANGENAME) {
                                    $playerinfo = new Player($buy_player->getID());
                                    $checkname = new Player($buy_from, Player::LOADTYPE_NAME);

                                    if (!$playerinfo->isOnline()) {
                                        if (!$checkname->isLoaded()) {
                                            $playerinfo->setName($buy_from);
                                            $playerinfo->save();

                                            $account_logged->setPremiumPoints($account_logged->getPremiumPoints() - $buyOffer->getPoints());
                                            $account_logged->save();

                                            $errorsView = true;
                                            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<h1 class="text-center fw-bold">Seu nome foi alterado para ' . $buy_from . '!</h1><p class="text-center">Agora você tem <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                        } else {
                                            $errorsView = true;
                                            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">Desculpe o nome <i>' . $buy_from . '</i> já existe. Selecione outro nome.</p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                        }
                                    } else {
                                        $errorsView = true;
                                        return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">' . $buy_name . ' precisa estar offline para concluir a transação.</p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                    }
                                } else if ($buyOffer->getOfferType() == ShopOffer::OFFERTYPE_ITEMLOGOUT) {
                                    $playerinfo = new Player($buy_player->getID());
                                    $playerslot = $playerinfo->getItems()->getSlot(10);

                                    if (!$playerinfo->isOnline()) {
                                        if (!$playerslot) {
                                            if ($playerinfo->getCap() >= $buyOffer->getFreeCap()) {
                                                $SQL->query('INSERT INTO player_items (player_id, pid, itemtype, count) VALUES (' . $playerinfo->getID() . ', ' . $SQL->quote($buyOffer->getPid() != 10 ? 10 : $buyOffer->getPid()) . ', ' . $SQL->quote($buyOffer->getItemId1()) . ', ' . $SQL->quote($buyOffer->getCount1()) . ');');

                                                $account_logged->setPremiumPoints($account_logged->getPremiumPoints() - $buyOffer->getPoints());
                                                $account_logged->save();

                                                $errorsView = true;
                                                return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<h1 class="text-center fw-bold">Item recebido para o jogador: ' . $buy_player->getName() . '!</h1><p class="text-center">Agora você tem <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                            } else {
                                                $errorsView = true;
                                                return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center">Você precisa de  <b>' . $buyOffer->getFreeCap() . ' de cap ou mais!</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                            }
                                        } else {
                                            $errorsView = true;
                                            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center">Por favor, deixe o slot da seta em branco para receber o item!</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                        }
                                    } else {
                                        $errorsView = true;
                                        return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">' . $buy_name . ' precisa estar offline para concluir a transação.</p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                    }
                                } else if ($buyOffer->getOfferType() == ShopOffer::OFFERTYPE_REDSKULL) {
                                    $playerinfo = new Player($buy_player->getID());

                                    if (!$playerinfo->isOnline()) {
                                        if ($playerinfo->getSkull() == '4' and $playerinfo->getSkullTime() > '0') {
                                            $SQL->query('UPDATE killers SET unjustified=0 WHERE id IN (SELECT kill_id FROM player_killers WHERE player_id=' . $playerinfo->getID() . ');');

                                            $playerinfo->setSkull(0);
                                            $playerinfo->setSkullTime(0);
                                            $playerinfo->save();

                                            $account_logged->setPremiumPoints($account_logged->getPremiumPoints() - $buyOffer->getPoints());
                                            $account_logged->save();

                                            $errorsView = true;
                                            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<h1 class="text-center fw-bold">RedSkull Removido!!</h1><p class="text-center">O redskull has been removed from the player ' . $buy_player->getName() . '</p><p class="text-center">Agora você tem <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                        } else {
                                            $errorsView = true;
                                            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">Você precisa está com redskull!</p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                        }
                                    } else {
                                        $errorsView = true;
                                        return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">' . $buy_name . ' precisa estar offline para concluir a transação.</p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                    }
                                } else if ($buyOffer->getOfferType() == ShopOffer::OFFERTYPE_UNBAN) {
                                    $ban = $SQL->query('SELECT * FROM ' . $SQL->tableName('bans') . ' WHERE value = ' . $account_logged->getID() . ';')->fetch();
                                    if ($ban['value'] == $account_logged->getID()) {
                                        if ($SQL->query('DELETE FROM bans WHERE value= ' . $account_logged->getID() . ' LIMIT 1;')) {
                                        } else {
                                            $SQL->query('DELETE FROM bans WHERE account= ' . $account_logged->getID() . ' LIMIT 1;');
                                        }

                                        $account_logged->setPremiumPoints($account_logged->getPremiumPoints() - $buyOffer->getPoints());
                                        $account_logged->save();

                                        $errorsView = true;
                                        return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<h1 class="text-center fw-bold">Ban exluído!</h1><p class="text-center">O ban da sua conta foi excluído.</p><p class="text-center">Agora você tem <b>' . $buyOffer->getPoints() . ' ' . config('otserver.pagseguro.productName') . '</b></p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                    } else {
                                        $errorsView = true;
                                        return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">Você não tem banimentos em sua conta!</p><a href="' . route('shop.index') . '" class="sbutton-blue d-block mx-auto">Voltar a Loja</a>');
                                    }
                                }
                            } else {
                                $errorsView = true;
                                return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">O jogador com o nome <b> ' . $buy_name . '</b> não existe. Por favor, <a href="' . route('shop.selectplayer', ['buyId' => $buy_id]) . '">selecione outro nome</a></p>');
                            }
                        } else {
                            $errorsView = true;
                            return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">Formato de nome inválido. Por favor, <a href="' . route('shop.selectplayer', ['buyId' => $buy_id]) . '">selecione outro nome</a> ou entre em contato com o administrador.</p>');
                        }
                    } else {
                        $errorsView = true;
                        return view($view, ['errorsView' => $errorsView, 'logged' => $logged])->withErrors('<p class="text-center fw-bold">Para este item, você precisa de <b> ' . $buyOffer->getPoints() . ' </b> pontos. Você tem apenas <b> ' . $account_logged->getPremiumPoints() . ' </b> ' . config('otserver.pagseguro.productName') . '. Selecione outro item ou compre ' . config('otserver.pagseguro.productName') . '.</p>');
                    }
                }
            }
        }
    }
}
