<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Otserver\DatabaseList;
use Otserver\Functions;
use Otserver\SQL_Order;
use Otserver\SQL_Field;
use Otserver\Guild;
use Otserver\Player;
use Otserver\Visitor;
use Otserver\Website;
use Otserver\GuildRank;

class GuildsController extends Controller
{
    public function index(Request $request)
    {
        $guilds_list = new DatabaseList('Guild');
        $guilds_list->addOrder(new SQL_Order(new SQL_Field('name'), SQL_Order::ASC));

        return view('index.guilds.index', [
            'guilds_list' => $guilds_list
        ]);
    }

    public function show($guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = 'index.guilds.show';

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        //check is it vice or/and leader account (leader has vice + leader rights)
        $guild_leader_char = $guild->getOwner();
        $rank_list = $guild->getGuildRanksList();
        $guild_leader = FALSE;
        $guild_vice = FALSE;
        $level_in_guild = 0;
        $players_from_account_in_guild = [];

        if ($logged) {
            $account_players = $account_logged->getPlayers();
            foreach ($account_players as $player) {
                $players_from_account_ids[] = $player->getId();
                $player_rank = $player->getRank();
                if (!empty($player_rank))
                    foreach ($rank_list as $rank_in_guild)
                        if ($rank_in_guild->getId() == $player_rank->getId()) {
                            $players_from_account_in_guild[] = $player->getName();
                            if ($player_rank->getLevel() > 1) {
                                $guild_vice = TRUE;
                                $level_in_guild = $player_rank->getLevel();
                            }
                            if ($guild->getOwner()->getId() == $player->getId()) {
                                $guild_vice = TRUE;
                                $guild_leader = TRUE;
                            }
                        }
            }
        }

        //show guild page
        $description = $guild->getDescription();
        $newlines   = array("\r\n", "\n", "\r");
        $description_with_lines = str_replace($newlines, '<br />', $description, $count);

        if ($count < config('otserver.site.guild_description_lines_limit'))
            $description = $description_with_lines;

        $guild_owner = $guild->getOwner();

        if ($guild_owner->isLoaded())
            $guild_owner = $guild_owner->getName();

        $invited_list = $guild->listInvites();
        $show_accept_invite = 0;

        if (count($invited_list) > 0) {
            foreach ($invited_list as $invited_player) {
                if (count($account_players) > 0) {
                    foreach ($account_players as $player_from_acc)
                        if ($player_from_acc->getName() == $invited_player->getName())
                            $show_accept_invite++;
                }
            }
        }

        return view($view, [
            'account_logged' => $account_logged,
            'account_players' => $account_logged->getPlayers(),
            'logged' => $logged,
            'guild' => $guild,
            'description' => $description,
            'guild_owner' => $guild_owner,
            'guild_leader_char' => $guild_leader_char,
            'guild_leader' => $guild_leader,
            'guild_vice' => $guild_vice,
            'level_in_guild' => $level_in_guild,
            'players_from_account_ids' => $players_from_account_ids,
            'rank_list' => $rank_list,
            'show_accept_invite' => $show_accept_invite,
            'players_from_account_in_guild' => $players_from_account_in_guild,
            'errorsView' => $errorsView
        ]);
    }

    public function changerank(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = 'index.guilds.changerank';

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode mudar de rank.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $guild_vice = false;
        $account_players = $account_logged->getPlayers(true);

        foreach ($account_players as $player) {
            $player_rank = $player->getRank(true);
            if (!empty($player_rank))
                foreach ($rank_list as $rank_in_guild)
                    if ($rank_in_guild->getId() == $player_rank->getId()) {
                        $players_from_account_in_guild[] = $player->getName();
                        if ($player_rank->getLevel() > 1) {
                            $guild_vice = true;
                            $level_in_guild = $player_rank->getLevel();
                        }
                        if ($guild->getOwner(true)->getId() == $player->getId()) {
                            $guild_vice = true;
                            $guild_leader = true;
                        }
                    }
        }

        if ($guild_vice) {
            $rid = 0;
            $sid = 0;
            foreach ($rank_list as $rank) {
                if ($guild_leader || $rank->getLevel() < $level_in_guild) {
                    $ranks[$rid]['0'] = $rank->getId();
                    $ranks[$rid]['1'] = $rank->getName();
                    $rid++;
                    $players_with_rank = $rank->getPlayers(true);
                    if (count($players_with_rank) > 0) {
                        foreach ($players_with_rank as $player) {
                            if ($guild->getOwner(true)->getId() != $player->getId() || $guild_leader) {
                                $players_with_lower_rank[$sid]['0'] = htmlspecialchars($player->getName());
                                $players_with_lower_rank[$sid]['1'] = htmlspecialchars($player->getName()) . ' (' . htmlspecialchars($rank->getName()) . ')';
                                $sid++;
                            }
                        }
                    }
                }
            }
        } else {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Erro. Você não é um líder ou vice-líder na guild ' . htmlspecialchars($guild->getName()) . '.');
        }

        if ($request->method() == "GET") {
            return view($view, [
                'guild' => $guild,
                'players_with_lower_rank' => $players_with_lower_rank,
                'ranks' => $ranks,
                'errorsView' => $errorsView,
            ]);
        }

        if ($request->method() == "POST") {
            $player_name = stripslashes($request->input('name'));
            $new_rank = (int)$request->input('rankid');

            $rank = new GuildRank();
            $rank->load($new_rank);

            if (!$rank->isLoaded()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('A classificação com este ID não existe.');
            }

            if ($level_in_guild <= $rank->getLevel() && !$guild_leader) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não pode definir classificações com nível igual ou superior ao seu.');
            }

            $player_to_change = new Player();
            $player_to_change->find($player_name);

            if (!$player_to_change->isLoaded()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Jogador com nome ' . htmlspecialchars($player_name) . '</b> não existe.');
            } else {
                $player_in_guild = false;
                if ($guild->getName() == $player_to_change->getRank(true)->getGuild()->getName() || $guild_leader) {
                    $player_in_guild = true;
                    $player_has_lower_rank = false;
                    if ($player_to_change->getRank(true)->getLevel() < $level_in_guild || $guild_leader)
                        $player_has_lower_rank = true;
                }
            }

            $rank_in_guild = false;
            foreach ($rank_list as $rank_from_guild)
                if ($rank_from_guild->getId() == $rank->getId())
                    $rank_in_guild = true;

            if (!$player_in_guild) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Este jogador não está na sua guild.');
            }

            if (!$rank_in_guild) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Esta classificação não está na sua guild.');
            }

            if (!$player_has_lower_rank) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Este jogador tem uma classificação superior na guild do que você. Você não pode mudar sua classificação.');
            }

            $errorsView = true;
            $player_to_change->setRank($rank);
            $player_to_change->save();

            return view($view, [
                'guild' => $guild,
                'players_with_lower_rank' => $players_with_lower_rank,
                'ranks' => $ranks,
                'errorsView' => $errorsView,
            ])->withErrors('A classificação do jogador <b>' . htmlspecialchars($player_to_change->getName()) . '</b> foi alterada para <b>' . htmlspecialchars($rank->getName()) . '</b>.');
        }
    }

    public function deleteinvite(Request $request, $guildId, $name)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.deleteinvite";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não está logado. Você não pode excluir convites.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $rank_list = $guild->getRanks(true);
        $rank_list->addOrder(new SQL_Order(new SQL_Field('level'), SQL_Order::DESC));
        $guild_leader = false;
        $guild_vice = false;
        $account_players = $account_logged->getPlayers();
        foreach ($account_players as $player) {
            $player_rank = $player->getRank(true);
            if (!empty($player_rank)) {
                foreach ($rank_list as $rank_in_guild) {
                    if ($rank_in_guild->getId() == $player_rank->getId()) {
                        $players_from_account_in_guild[] = $player->getName();
                        if ($player_rank->getLevel() > 1) {
                            $guild_vice = TRUE;
                            $level_in_guild = $player_rank->getLevel();
                        }
                        if ($guild->getOwner(true)->getId() == $player->getId()) {
                            $guild_vice = TRUE;
                            $guild_leader = TRUE;
                        }
                    }
                }
            }
        }

        $player = new Player(urldecode($name), Player::LOADTYPE_NAME);
        if (!$player->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('O player com esse nome <b>' . $name . '</b> não existe.');
        }

        if (!$guild_vice) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não é um líder ou vice-líder da guild <b>' . $player->getName() . '</b>.');
        }

        $invited_list = $guild->listInvites();
        if (count($invited_list) > 0) {
            $is_invited = FALSE;
            foreach ($invited_list as $invited)
                if ($invited->getName() == $player->getName())
                    $is_invited = TRUE;
            if (!$is_invited) {
                $errorsView = true;
                return view($view, [
                    'errorsView' => $errorsView,
                    'guild' => $guild
                ])->withErrors('<b>' . $player->getName() . '</b> não é convidado para sua guild.');
            }
        } else {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Ninguém foi convidado para sua guild.');
        }

        if ($request->method() == "GET") {
            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'player' => $player,
                'errorsView' => $errorsView,
            ]);
        }

        if ($request->method() == "POST") {
            $errorsView = true;
            $guild->deleteInvite($player);

            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'player' => $player,
                'errorsView' => $errorsView,
            ])->withErrors("O jogador com o nome " . htmlspecialchars($player->getName()) . " foi convidado para sua guild.");
        }
    }

    public function invite(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.invite";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode convidar players.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $rank_list = $guild->getRanks(true);
        $rank_list->addOrder(new SQL_Order(new SQL_Field('level'), SQL_Order::DESC));
        $guild_leader = false;
        $guild_vice = false;

        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            $player_rank = $player->getRank(true);
            if (!empty($player_rank)) {
                foreach ($rank_list as $rank_in_guild) {
                    if ($rank_in_guild->getId() == $player_rank->getId()) {
                        $players_from_account_in_guild[] = $player->getName();
                        if ($player_rank->getLevel() > 1) {
                            $guild_vice = true;
                            $level_in_guild = $player_rank->getLevel();
                        }

                        if ($guild->getOwner()->getId() == $player->getId()) {
                            $guild_vice = true;
                            $guild_leader = true;
                        }
                    }
                }
            }
        }

        if (!$guild_vice) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não é um líder ou vice-líder da guild <b>' . $guild->getName() . '</b>.' . $level_in_guild);
        }

        if ($request->method() == "GET") {
            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'errorsView' => $errorsView
            ]);
        }

        if ($request->method() == "POST") {
            $name = request()->input('name');

            $player = new Player($name, Player::LOADTYPE_NAME);

            if (!$player->isLoaded()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("O jogador <b>" . htmlspecialchars($name) . "</b> não existe.");
            }

            $messages = [
                'name.check_name' => "Formato do nome do player inválido.",
                'name.check_player' => "O player com esse nome <b>:input</b> não existe.",
                'name.check_player_exist_guild' => "O jogador com esse nome <b>:input</b> já está em guild. Ele tem que sair da guild antes de convidá-lo.",
                'name.check_player_world' => "O jogador <b>:input</b> é de um mundo diferente da guild.",
                'name.check_invite_player' => "O jogador <b>:input</b> já está convidado para essa guild."
            ];

            $rules = [
                'name' => [
                    'required',
                    'check_name:name',
                    'check_player:name',
                    'check_player_exist_guild',
                    'check_player_world',
                    'check_invite_player'
                ],
            ];

            $names = [
                'name' => "Nome do Player"
            ];

            Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
                if (!Functions::check_name($value))
                    return false;
                return true;
            });

            Validator::extend('check_player', function ($attribute, $value, $parameters, $validator) {
                $player = new Player($value, Player::LOADTYPE_NAME);
                if (!$player->isLoaded())
                    return false;
                return true;
            });

            Validator::extend('check_player_exist_guild', function ($attribute, $value, $parameters, $validator) use ($player) {
                $rank_of_player = $player->getRank(true);
                if (!empty($rank_of_player))
                    return false;
                return true;
            });

            Validator::extend('check_player_world', function ($attribute, $value, $parameters, $validator) use ($guild, $player) {
                if ($guild->getWorld() != $player->getWorld())
                    return false;
                return true;
            });

            Validator::extend('check_invite_player', function ($attribute, $value, $parameters, $validator) use ($guild, $player) {
                $invited_list = $guild->getInvitations(true);
                if (count($invited_list) > 0) {
                    foreach ($invited_list as $invited) {
                        if ($invited->getName() == $player->getName()) {
                            return false;
                        }
                    }
                }
                return true;
            });

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                $errorsView = false;
                return view($view, [
                    'account_logged' => $account_logged,
                    'logged' => $logged,
                    'guild' => $guild,
                    'errorsView' => $errorsView,
                ])->withErrors($validator);
            }

            $guild->invite($player, true);

            if ($guild->isInvited($player->getID(), true))
                $errorsView = true;

            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'errorsView' => $errorsView,
            ])->withErrors("O jogador com o nome " . htmlspecialchars($player->getName()) . " foi convidado para sua guild.");
        }
    }

    public function acceptinvite(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.acceptinvite";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, [
                'guild' => $guild,
                'errorsView' => $errorsView
            ])->withErrors('Você não está logado. Você não pode convidar players.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'guild' => $guild,
                'errorsView' => $errorsView
            ])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $list_of_invited_players = [];
        $acc_invited = false;
        $account_players = $account_logged->getPlayers();
        $invited_list = $guild->getInvitations(true);
        if (count($invited_list) > 0) {
            foreach ($invited_list as $invited) {
                foreach ($account_players as $player_from_acc) {
                    if ($invited->getName() == $player_from_acc->getName()) {
                        $acc_invited = true;
                        $list_of_invited_players[] = $player_from_acc->getName();
                    }
                }
            }
        }

        if (!$acc_invited) {
            $errorsView = true;
            return view($view, [
                'guild' => $guild,
                'errorsView' => $errorsView
            ])->withErrors('Qualquer personagem da sua conta não é convidado para <b>' . $guild->getName() . '</b>.');
        }

        if ($request->method() == "GET") {
            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'list_of_invited_players' => $list_of_invited_players,
                'errorsView' => $errorsView
            ]);
        }

        if ($request->method() == "POST") {
            $name = request()->input('name');

            $player = new Player(urldecode($name), Player::LOADTYPE_NAME);

            if (!$player->isLoaded()) {
                $errorsView = true;
                return view($view, [
                    'errorsView' => $errorsView,
                    'guild' => $guild
                ])->withErrors("O jogador <b>" . htmlspecialchars($name) . "</b> não existe.");
            }

            $messages = [
                'name.check_player' => "O player com esse nome <b>:input</b> não existe.",
                'name.check_player_exist_guild' => "O jogador com esse nome <b>:input</b> já está em guild. Ele tem que sair da guild antes de convidá-lo.",
                'name.check_player_world' => "O jogador <b>:input</b> é de um mundo diferente da guild.",
                'name.check_invite_player' => "O jogador <b>:input</b> não está convidado para guild <b>" . $guild->getName() . "</b>."
            ];

            $rules = [
                'name' => [
                    'required',
                    'check_player:name',
                    'check_player_exist_guild',
                    'check_player_world',
                    'check_invite_player'
                ],
            ];

            $names = [
                'name' => "Nome do Player"
            ];

            Validator::extend('check_player', function ($attribute, $value, $parameters, $validator) use ($player) {
                if (!$player->isLoaded())
                    return false;
                return true;
            });

            Validator::extend('check_player_exist_guild', function ($attribute, $value, $parameters, $validator) use ($player) {
                $rank_of_player = $player->getRank(true);
                if (!empty($rank_of_player))
                    return false;
                return true;
            });

            Validator::extend('check_player_world', function ($attribute, $value, $parameters, $validator) use ($guild, $player) {
                if ($guild->getWorld() != $player->getWorld())
                    return false;
                return true;
            });

            Validator::extend('check_invite_player', function ($attribute, $value, $parameters, $validator) use ($guild, $player) {
                $invited_list = $guild->getInvitations(true);
                if (count($invited_list) > 0) {
                    foreach ($invited_list as $invited) {
                        if ($invited->getName() == $player->getName()) {
                            return true;
                        }
                    }
                }
                return false;
            });

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                $errorsView = false;
                return view($view, [
                    'account_logged' => $account_logged,
                    'logged' => $logged,
                    'guild' => $guild,
                    'list_of_invited_players' => $list_of_invited_players,
                    'errorsView' => $errorsView,
                ])->withErrors($validator);
            }

            $errorsView = true;
            $guild->acceptInvite($player);

            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'list_of_invited_players' => $list_of_invited_players,
                'errorsView' => $errorsView,
            ])->withErrors("Bem vindo, <b>{$player->getName()}</b> agora você menbro da guild <b>{$guild->getName()}</b>.");
        }
    }

    public function kickplayer(Request $request, $guildId, $name)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.kickplayer";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode chutar personagens.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $rank_list = $guild->getRanks(true);
        $rank_list->addOrder(new SQL_Order(new SQL_Field('level'), SQL_Order::DESC));
        $guild_leader = false;
        $guild_vice = false;
        $account_players = $account_logged->getPlayers(true);

        foreach ($account_players as $player) {
            $player_rank = $player->getRank(true);
            if (!empty($player_rank)) {
                foreach ($rank_list as $rank_in_guild) {
                    if ($rank_in_guild->getId() == $player_rank->getId()) {
                        $players_from_account_in_guild[] = $player->getName();
                        if ($player_rank->getLevel() > 1) {
                            $guild_vice = true;
                            $level_in_guild = $player_rank->getLevel();
                        }
                        if ($guild->getOwner(true)->getId() == $player->getId()) {
                            $guild_vice = true;
                            $guild_leader = true;
                        }
                    }
                }
            }
        }

        if (!$guild_leader && $level_in_guild < 3) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild,
                'player' => $player
            ])->withErrors("Você não é um líder da guild <b>" . htmlspecialchars($guild->getName()) . "</b>.  Você não pode dispensar jogadores.");
        }

        $player = new Player(urldecode($name), Player::LOADTYPE_NAME);

        if (!$player->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("O jogador <b>" . htmlspecialchars($name) . "</b> não existe.");
        } else {
            if ($player->getRank(true)->getGuild()->getName() != $guild->getName()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("O personagem <b>" . htmlspecialchars($name) . "</b> não é da sua guild.");
            }
        }

        if ($player->getRank(true)->getLevel() >= $level_in_guild && !$guild_leader) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não pode dispensar o personagem <b>' . htmlspecialchars($name) . '</b> Nível de acesso muito alto.');
        }

        if ($guild->getOwner(true)->getName() == $player->getName()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Não é possível dispensar o dono da guild!');
        }

        if ($request->method() == "GET") {
            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'player' => $player,
                'errorsView' => $errorsView
            ]);
        }

        if ($request->method() == "POST") {
            $errorsView = true;

            $player->setRank();
            $player->save();

            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'guild' => $guild,
                'player' => $player,
                'errorsView' => $errorsView,
            ])->withErrors("O player {$player->getName()} foi dispensado da sua guild.");
        }
    }

    public function leaveguild(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.leaveguild";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_owner_id = $guild->getOwner(true)->getId();
        $account_players = $account_logged->getPlayers(true);
        $array_of_player_ig = [];
        foreach ($account_players as $player_fac) {
            $player_rank = $player_fac->getRank(true);
            if (!empty($player_rank))
                if ($player_rank->getGuild(true)->getId() == $guild->getId())
                    if ($guild_owner_id != $player_fac->getId())
                        $array_of_player_ig[] = $player_fac->getName();
        }

        if (count($array_of_player_ig) == 0) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Não há personagem na sua conta para deixa nessa guild.');
        }

        if ($request->method() == "GET") {
            return view($view, [
                'guild' => $guild,
                'array_of_player_ig' => $array_of_player_ig,
                'errorsView' => $errorsView
            ]);
        }

        if ($request->method() == "POST") {
            $name = urldecode(request()->input('name'));

            $player = new Player();
            $player->find($name);
            if (!$player->isLoaded()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Personagem <b>' . htmlspecialchars($name) . '</b> não existe.');
            } else {
                if ($player->getAccount()->getId() != $account_logged->getId()) {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Personagem <b>' . htmlspecialchars($name) . '</b> não é da sua conta!');
                }
            }

            $player_loaded_rank = $player->getRank(true);
            if (!empty($player_loaded_rank) && $player_loaded_rank->isLoaded()) {
                if ($player_loaded_rank->getGuild(true)->getId() != $guild->getId()) {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Personagem <b>' . htmlspecialchars($name) . '</b> não é da guild <b>' . htmlspecialchars($guild->getName()) . '</b>.');
                }
            } else {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Personagem <b>' . htmlspecialchars($name) . '</b> não está em nenhuma guild.');
            }

            if ($guild_owner_id == $player->getId()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não pode deixar a guild. Você é dono de uma guild.');
            }

            $errorsView = true;

            $player->setRank();
            $player->save();

            return view($view, [
                'guild' => $guild,
                'player' => $player,
                'errorsView' => $errorsView,
            ])->withErrors("O Jogador com o nome <b>" . htmlspecialchars($player->getName()) . "</b> deixa a guild <b>" . htmlspecialchars($guild->getName()));
        }
    }

    public function createguild(Request $request)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.createguild";

        $array_of_player_nig = [];

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView])->withErrors("Você não está logado. Você não pode criar uma guild.");
        }

        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            $player_rank = $player->getRank();
            if (empty($player_rank)) {
                if ($player->getLevel() >= config('otserver.site.guild_need_level')) {
                    if (!config('otserver.site.guild_need_pacc') || $account_logged->isPremium()) {
                        $array_of_player_nig[(int)$player->getID()] = $player->getName();
                    }
                }
            }
        }

        if (count($array_of_player_nig) == 0) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView])->withErrors("Na sua conta, todos os personagens estão em guilds ou têm um nível muito baixo para criar uma nova guild.");
        }

        if ($request->method() == "GET") {
            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'array_of_player_nig' => $array_of_player_nig,
                'errorsView' => $errorsView
            ]);
        }

        if ($request->method() == "POST") {
            $new_guild_name = request()->input('new_guild_name');
            $new_guild_player = urldecode(request()->input('new_guild_player'));

            $requestAll = [
                'new_guild_name' => $new_guild_name,
                'new_guild_player' => $new_guild_player
            ];

            $messages = [
                'new_guild_name.check_guild_name' => "Formato inválido do nome da Guild.",
                'new_guild_name.check_guild' => "Guild <b>:input</b> já existe. Selecione outro nome.",
                'new_guild_player.check_name' => "Formato do nome do player inválido.",
                'new_guild_player.check_player' => "O player com esse nome <b>:input</b> não existe.",
                'new_guild_player.check_is_player_account' => "Character <b>:input</b> isn\'t on your account or is already in guild."
            ];

            $rules = [
                'new_guild_name' => ['required', 'check_guild_name:new_guild_name', 'check_guild:new_guild_name'],
                'new_guild_player' => ['required', 'check_name:new_guild_player', 'check_player:new_guild_player', "check_is_player_account:new_guild_player"],
            ];

            $names = [
                'new_guild_name' => "Nome da Guild",
                'new_guild_player' => "Nome do Player"
            ];

            Validator::extend('check_guild_name', function ($attribute, $value, $parameters, $validator) {
                if (!Functions::check_guild_name($value))
                    return false;
                return true;
            });

            Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
                if (!Functions::check_name($value))
                    return false;
                return true;
            });

            Validator::extend('check_player', function ($attribute, $value, $parameters, $validator) {
                $player = new Player($value, Player::LOADTYPE_NAME);
                if (!$player->isLoaded())
                    return false;
                return true;
            });

            Validator::extend('check_guild', function ($attribute, $value, $parameters, $validator) {
                $guild = new Guild($value, Player::LOADTYPE_NAME);
                if ($guild->isLoaded())
                    return false;
                return true;
            });

            Validator::extend('check_is_player_account', function ($attribute, $value, $parameters, $validator) use ($array_of_player_nig, $player) {
                $bad_char = true;
                foreach ($array_of_player_nig as $nick_from_list) {
                    if ($nick_from_list == $player->getName())
                        $bad_char = false;
                }
                if ($bad_char)
                    return false;
                return true;
            });

            $validator = Validator::make($requestAll, $rules, $messages);
            $validator->setAttributeNames($names);

            if ($player->getLevel() < config('otserver.site.guild_need_level')) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView])->withErrors("O player <b>' . $new_guild_player . '</b> tem um nível muito baixo. Para criar uma guild, você precisa de um personagem com nível <b>" . config('otserver.site.guild_need_level') . "</b>.");
            }

            if (config('otserver.site.guild_need_pacc') && !$account_logged->isPremium()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView])->withErrors("O player <b>' . $new_guild_player . '</b> é Conta Grátis. Para criar uma guild você precisa de uma Conta Premium.");
            }

            if ($validator->fails()) {
                $errorsView = false;
                return view($view, [
                    'account_logged' => $account_logged,
                    'logged' => $logged,
                    'array_of_player_nig' => $array_of_player_nig,
                    'errorsView' => $errorsView,
                ])->withErrors($validator);
            }

            $new_guild = new Guild();
            $new_guild->setCreationData(time());
            $new_guild->setName($new_guild_name);
            $new_guild->setOwner($player);
            $new_guild->setDescription('Nova guild. O líder deve editar este texto :)');
            $new_guild->setGuildLogo('default_guild_logo.gif');
            $new_guild->setShow(1);
            $new_guild->save();
            Website::getDBHandle()->setPrintQueries(true);

            $ranks = $new_guild->getGuildRanksList(true);
            foreach ($ranks as $rank) {
                if ($rank->getLevel() == 3) {
                    $player->setRank($rank);
                    $player->save();
                }
            }

            return view($view, [
                'account_logged' => $account_logged,
                'logged' => $logged,
                'array_of_player_nig' => $array_of_player_nig,
                'errorsView' => $errorsView,
            ])->withErrors('<h1>Parabéns!</h1><p>Você criou a guild <b>' . htmlspecialchars($new_guild_name) . '.</p><p>' . htmlspecialchars($player->getName()) . '</b> é o líder desta guild. Agora você pode convidar jogadores, mudar a imagem, a descrição e os motivos da guild. Pressione enviar para abrir o gerenciador da guild.</p>');
        }
    }

    public function manager(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = 'index.guilds.manager';

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <>' . $guildId . '</> não existe.');
        }

        $guild_leader_char = $guild->getOwner();
        $rank_list = $guild->getGuildRanksList();
        $guild_leader = false;
        $account_players = $account_logged->getPlayers();
        foreach ($account_players as $player) {
            if ($guild_leader_char->getId() == $player->getId()) {
                $guild_vice = true;
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        return view($view, [
            'account_logged' => $account_logged,
            'account_players' => $account_players,
            'logged' => $logged,
            'guild' => $guild,
            'rank_list' => $rank_list,
            'guild_leader_char' => $guild_leader_char,
            'guild_leader' => $guild_leader,
            'guild_vice' => $guild_vice,
            'level_in_guild' => $level_in_guild,
            'errorsView' => $errorsView,
        ]);
    }

    public function changelogo(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.changelogo";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);

        foreach ($account_players as $player) {
            if ($guild_leader_char->getId() == $player->getId()) {
                $guild_vice = true;
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não é um líder de guild!');
        }

        if ($request->method() == "GET") {
            return view($view, [
                'guild' => $guild,
                'errorsView' => $errorsView
            ]);
        }

        if ($request->method() == "POST") {
            $file = request()->file('newlogo');
            $max_image_size_b = config('otserver.site.guild_image_size_kb') * 1024;

            if (
                request()->hasFile('newlogo') &&
                $file->isValid() &&
                $file->getSize() <= $max_image_size_b
            ) {
                // Define um aleatório para o arquivo baseado no timestamps atual
                $name = uniqid(date('HisYmd'));
                // Recupera a extensão do arquivo
                $extension = request()->file('newlogo')->extension();
                // Define finalmente o nome
                $nameFile = "{$name}.{$extension}";
                // Faz o upload:
                $upload = request()->file('newlogo')->storeAs('public/guilds/', $nameFile);
                // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao
                // Verifica se NÃO deu certo o upload (Redireciona de volta)
                if (!$upload) {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView, 'guild' => $guild])
                        ->back()
                        ->withErrors('Falha ao fazer upload')
                        ->withInput();
                }

                $guild->setGuildLogo($nameFile);
            } else {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('O arquivo carregado não é uma imagem!');
            }

            $errorsView = true;
            $guild->save();

            return view($view, [
                'guild' => $guild,
                'errorsView' => $errorsView,
            ])->withErrors("A logo da guild foi alterada com sucesso!");
        }
    }

    public function deleterank(Request $request, $guildId, $rankId)
    {
        $errorsView = false;
        $saved = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.deleterank";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            return view($view, ['guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            return view($view, ['guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            if ($guild->getOwner(true)->getId() == $player->getId()) {
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            return view($view, [
                'guild' => $guild
            ])->withErrors('Você não é um líder de guild!');
        }

        $rank = new GuildRank();
        $rank->load($rankId);

        if (!$rank->isLoaded()) {
            return view($view, ['guild' => $guild])->withErrors('Classifique com ID ' . $rankId . 'não existe.');
        } else {
            if ($rank->getGuild()->getId() != $guild->getId()) {
                return view($view, ['guild' => $guild])->withErrors('Classifique com ID ' . $rankId . ' não é da sua guild.');
            } else {
                if (count($rank_list) < 2) {
                    return view($view, ['guild' => $guild])->withErrors('Você tem apenas 1 classificação em sua guild. Você não pode deletar esta classificação.');
                } else {
                    $players_with_rank = $rank->getMembers(true);
                    $players_with_rank_number = count($players_with_rank);
                    if ($players_with_rank_number > 0) {
                        foreach ($rank_list as $checkrank)
                            if ($checkrank->getId() != $rank->getId())
                                if ($checkrank->getLevel() <= $rank->getLevel())
                                    $new_rank = $checkrank;
                        if (empty($new_rank)) {
                            $new_rank = new GuildRank();
                            $new_rank->setGuild($guild);
                            $new_rank->setLevel($rank->getLevel());
                            $new_rank->setName('Novo nível de classificação ' . $rank->getLevel());
                            $new_rank->save();
                        }
                        foreach ($players_with_rank as $player_in_guild) {
                            $player_in_guild->setRank($new_rank);
                            $player_in_guild->save();
                        }
                    }
                    $rank->delete();
                    $saved = true;
                }
            }
        }

        if ($saved) {
            return view($view, [
                'guild' => $guild,
            ])->withErrors('A classificação <b> ' . htmlspecialchars($rank->getName()) . ' </b> foi excluída. Jogadores com esta classificação agora possuem outra classificação.');
        } else {
            return view($view, [
                'guild' => $guild,
            ])->withErrors('A classificação não foi excluída.');
        }
    }

    public function addrank(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.addrank";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            if ($guild_leader_char->getId() == $player->getId()) {
                $guild_vice = true;
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não é um líder de guild!');
        }

        if ($request->method() == "GET") {
            $errorsView = false;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild]);
        }

        if ($request->method() == "POST") {
            $ranknew = request()->input('rank_name');

            if (!Functions::check_rank_name($ranknew)) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Formato de nome de classificação inválido.');
            }

            if ($guild_leader) {
                $new_rank = new GuildRank();
                $new_rank->setGuild($guild);
                $new_rank->setLevel(1);
                $new_rank->setName($ranknew);
                $new_rank->save();

                redirect("guilds/manager/{$guildId}");
            } else {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não é um líder de guild!');
            }
        }
    }

    public function changedescription(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.changedescription";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            if ($guild->getOwner(true)->getId() == $player->getId()) {
                $guild_vice = true;
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não é um líder de guild!');
        }

        if ($request->method() == "GET") {
            $errorsView = false;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild]);
        }

        if ($request->method() == "POST") {
            $description = htmlspecialchars(substr(trim(request()->input('description')), 0, config('otserver.site.guild_description_chars_limit')));
            $guild->set('description', $description);
            $guild->save();
            $saved = true;
        }

        if ($saved) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("As alterações foram salvas!");
        } else {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("Erros ao salvar alterações!");
        }
    }

    public function passleadership(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.passleadership";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            if ($guild->getOwner(true)->getId() == $player->getId()) {
                $guild_vice = true;
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não é um líder de guild!');
        }

        if ($request->method() == "GET") {
            $errorsView = false;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild]);
        }

        if ($request->method() == "POST") {
            $pass_to = request()->input('player');

            if (!Functions::check_name($pass_to)) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Formato de nome de jogador inválido.');
            }

            $to_player = new Player();
            $to_player->find($pass_to);
            if (!$to_player->isLoaded()) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Jogador com nome <b> ' . htmlspecialchars($pass_to) . '</b> não existe.');
            }

            $to_player_rank = $to_player->getRank();

            if (!empty($to_player_rank)) {
                $to_player_guild = $to_player_rank->getGuild();
                if ($to_player_guild->getId() != $guild->getId()) {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Jogador com nome <b> ' . htmlspecialchars($to_player->getName()) . '</b> não é da sua guild.');
                }
            } else {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Jogador com nome <b> ' . htmlspecialchars($to_player->getName()) . '</b> não é da sua guild.');
            }

            $guild->setOwner($to_player);
            $guild->save();

            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("As alterações foram salvas!");
        }
    }

    public function deleteguild(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.deleteguild";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);

        foreach ($account_players as $player) {
            if ($guild->getOwner(true)->getId() == $player->getId()) {
                $guild_vice = true;
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não é um líder de guild!');
        }

        if ($request->method() == "GET") {
            $errorsView = false;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild]);
        }

        if ($request->method() == "POST") {
            $guild->delete();
            $saved = true;

            if ($saved) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guilda com ID <b> ' . $guildId . ' </b> foi excluída.');
            } else {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("Erros ao salvar alterações!");
            }
        }
    }

    public function deletebyadmin(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.deletebyadmin";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        if (Visitor::getAccount()->getPageAccess() >= config('otserver.site.access_admin_panel')) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não é administrador página!');
        }

        if ($request->method() == "GET") {
            $errorsView = false;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild]);
        }

        if ($request->method() == "POST") {
            $guild->delete();
            $saved = true;

            if ($saved) {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guilda com ID <b> ' . $guildId . ' </b> foi excluída.');
            } else {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("Erros ao salvar alterações!");
            }
        }
    }

    public function changemotd(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.changemotd";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            if ($guild->getOwner(true)->getId() == $player->getId()) {
                $guild_vice = true;
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
                'guild' => $guild
            ])->withErrors('Você não é um líder de guild!');
        }

        if ($request->method() == "GET") {
            $errorsView = false;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild]);
        }

        if ($request->method() == "POST") {
            $description = htmlspecialchars(substr(trim(request()->input('motd')), 0, config('otserver.site.guild_description_chars_limit')));
            $guild->set('description', $description);
            $guild->save();
            $saved = true;
        }

        if ($saved) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("As alterações foram salvas!");
        } else {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors("Erros ao salvar alterações!");
        }
    }

    public function saveranks(Request $request, $guildId)
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.saveranks";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $guild_leader_char = $guild->getOwner(true);
        $rank_list = $guild->getRanks(true);
        $guild_leader = false;
        $account_players = $account_logged->getPlayers(true);
        foreach ($account_players as $player) {
            if ($guild->getOwner(true)->getId() == $player->getId()) {
                $guild_leader = true;
                $level_in_guild = 3;
            }
        }

        if (!$guild_leader) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não é um líder de guild!');
        }

        if ($request->method() == "POST") {
            foreach ($rank_list as $rank) {
                $rank_id = $rank->getId();
                $name = request()->input($rank_id . '_name');
                $level = (int) request()->input($rank_id . '_level');
                if (Functions::check_rank_name($name)) {
                    $rank->setName($name);
                } else {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Invalid rank name. Please use only a-Z, 0-9 and spaces. Rank ID <b>' . $rank_id . '</b>.');
                }
                if ($level > 0 && $level < 4)
                    $rank->setLevel($level);
                else {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Invalid rank level. Contact with admin. Rank ID <b>' . $rank_id . '</b>.');
                }
                $rank->save();
            }
            return redirect()->route('guilds.manager', ['guildId' => $guildId]);
        }
    }

    public function cleanupplayers(Request $request)
    {
        # code...
    }

    public function changenick(Request $request, $guildId, $name = "")
    {
        $errorsView = false;
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $view = "index.guilds.changenick";

        $guild = new Guild();
        $guild->load((int)$guildId);

        if (!$logged) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Você não está logado. Você não pode sair da guild.');
        }

        if (!$guild->isLoaded()) {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Guild com ID <b>' . $guildId . '</b> não existe.');
        }

        $player = new Player();
        $player->find($name);
        $player_from_account = false;
        $new_nick = request()->input('nick');

        if (strlen($new_nick) <= 30) {
            if ($player->isLoaded()) {
                $account_players = $account_logged->getPlayers(true);
                if (count($account_players)) {
                    foreach ($account_players as $acc_player) {
                        if ($acc_player->getId() == $player->getId())
                            $player_from_account = true;
                    }
                    if ($player_from_account) {
                        $player->setGuildNick($new_nick);
                        $player->save();
                    } else {
                        $errorsView = true;
                        return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Este jogador não é de sua conta.');
                    }
                } else {
                    $errorsView = true;
                    return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Este jogador não é de sua conta.');
                }
            } else {
                $errorsView = true;
                return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Ocorreu um erro desconhecido.');
            }
        } else {
            $errorsView = true;
            return view($view, ['errorsView' => $errorsView, 'guild' => $guild])->withErrors('Nick de guild muito longo. Máx. 30 caracteres, seu: ' . strlen($new_nick));
        }
        return redirect()->route('guilds.show', ['guildId' => $guild->getId()]);
    }
}
