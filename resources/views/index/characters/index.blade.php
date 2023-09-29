@extends('layout.index')
@section('title', 'Characters')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Characters</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Infromações do Personagem:</div>
            <div class="main-content">
                <div class="row ms-md-0">
                    <div class="col-12 col-md-3" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                        <div class="row">
                            <div class="col-2 fw-bold">HP:</div>
                            <div class="col-10 d-flex flex-column justify-content-center">
                                <div class="progress position-relative">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="{{ $hpPercent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $hpPercent }}%"></div>
                                    <span class="position-absolute text-white w-100 text-center fw-bold">{{ $player->getHealth() }} / {{ $player->getHealthMax() }}</span>
                                </div>
                            </div>
                            <div class="col-2 fw-bold">Mana:</div>
                            <div class="col-10 d-flex flex-column justify-content-center">
                                <div class="progress position-relative">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info m-0" role="progressbar" aria-valuenow="{{ $manaPercent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $manaPercent }}%"></div>
                                    <span class="position-absolute text-white w-100 text-center fw-bold">{{ $player->getMana() }} / {{ $player->getManaMax() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="height: 350px;padding: 7px;">
                            @foreach ($list as $number_of_items_showed => $slot)
                                @if($slot == '8')
                                <div class="col-4 d-flex justify-content-center align-items-center flex-column" style="background-color: #d1d1d1; border:1px solid #ddd">
                                    <p class="m-0">Soul:</p>
                                    <p class="m-0">{{ $player->getSoul() }}</p>
                                </div>
                                @endif
                                @if($itemsList->getSlot($slot) === false)
                                <div class="col-4 d-flex justify-content-center align-items-center" style="background-color: #d1d1d1;border:1px solid #ddd">
                                    <img src="{{ asset(config('otserver.site.item_images_url')).'/'.$slot.config('otserver.site.item_images_extension') }}" class="img-fluid"/>
                                </div>
                                @else
                                <div class="col-4 d-flex justify-content-center align-items-center" style="background-color: #d1d1d1;border:1px solid #ddd">
                                    <img src="{{ asset(config('otserver.site.item_images_url')).'/'.$itemsList->getSlot($slot)->getID().config('otserver.site.item_images_extension') }}" onerror="this.onerror=null; this.src='{{ asset(config('otserver.site.item_images_url')).'/'.$slot.config('otserver.site.item_images_extension') }}'" width="45"/>
                                </div>
                                @endif
                                @if($slot == '8')
                                <div class="col-4 d-flex justify-content-center align-items-center flex-column" style="background-color: #d1d1d1;border:1px solid #ddd">
                                    <p class="m-0">Cap:</p>
                                    <p class="m-0">{{ $player->getCap() }}</p>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row mt-1">
                            <div class="col-4">Level:</div>
                            <div class="col-8">{{ $player->getLevel() }}</div>
                            <div class="col-4">Experience:</div>
                            <div class="col-8">{{ $player->getExperience() }} EXP</div>
                            <div class="col-4">Próx. Level:</div>
                            <div class="col-8 d-flex align-items-center">
                                <div title="<?= (100 - str_replace(',', '.', max(0, min(100, ($player->getExperience() - Functions::getExpForLevel($player->getLevel())) / (Functions::getExpForLevel($player->getLevel() + 1) - Functions::getExpForLevel($player->getLevel())) * 100)))) ?>% left" style="width: 100%; height: 5px; border: 1px solid #000;">
                                    <span style="background: red; width:<?= str_replace(',', '.', max(0, min(100, ($player->getExperience() - Functions::getExpForLevel($player->getLevel())) / (Functions::getExpForLevel($player->getLevel() + 1) - Functions::getExpForLevel($player->getLevel())) * 100))); ?>%;height: 3px;display:table;"></span>
                                </div>
                            </div>
                            <div class="col-12 text-center">Você precisa de <b>{{ bcsub(Functions::getExpForLevel($player->getLevel() + 1), $player->getExperience(), 0) }} EXP</b> para o Level <b>{{ ($player->getLevel() + 1) }}</b>.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9">
                        <ul class="list-group list-group-horizontal row m-0 p-0">
                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Nome:</li>
                            <li class="list-group-item col-12 col-md-9 {{ $player->isOnline() ? 'text-success' : 'text-danger' }} fw-bold text-center" style="border-top-width: 1px;">
                                {{ htmlspecialchars($player->getName()) }}
                                @if(!empty($skull))
                                <img src="{{ $skull }}">
                                @endif
                                <img src="{{ asset(config('otserver.site.flag_images_url'))."/{$account->getFlag()}".config('otserver.site.flag_images_extension') }}" title="Country: {{ $account->getFlag() }}" alt="{{ $account->getFlag() }}" />
                                @if($player->isBanned() || $account->isBanned())
                                <span class="text-danger">[BANNED]</span>
                                @endif
                                @if($player->isNamelocked())
                                <span class="text-danger">[NAMELOCKED]</span>
                                @endif
                            </li>
                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Roupa:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">
                                <img src="{{ asset(config('otserver.site.outfit_images_url'))."?id={$player->getLookType()}&addons={$player->getLookAddons()}&head={$player->getLookHead()}&body={$player->getLookBody()}&legs={$player->getLookLegs()}&feet={$player->getLookFeet()}" }}" class="img-fluid" />
                            </li>

                            @if(in_array($player->getGroup(), config('otserver.site.groups_support')))
                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Grupo:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ $player->getGroup() }}</li>
                            @endif

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Sexo:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ htmlspecialchars((($player->getSex() == 0) ? 'Mulher' : 'Homem')) }}</li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Vocação:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ htmlspecialchars(Website::getVocationName($player->getVocation())) }}</li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Level:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ htmlspecialchars($player->getLevel()) }}</li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Casa:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">
                                @if(count($house) != 0)
                                    {{ $house[0]['name'] }}
                                @else
                                    {{ _("Este jogador não possuí nenhuma casa.") }}
                                @endif
                            </li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Residente em:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ htmlspecialchars(config('otserver.towns_list')[$player->getWorldID()][$player->getTownID()]) }}</li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Mundo:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ config('otserver.site.worlds')[$player->getWorldID()] }}</li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Guild: </li>
                            @if(!empty($rank_of_player))
                                <li class="list-group-item col-12 col-md-9 text-center">{{ htmlspecialchars($rank_of_player->getName()) }} da guild <a href="{{ route('guilds.show', ['guildId' => $rank_of_player->getGuild()->getID()]) }}">{{ htmlspecialchars($rank_of_player->getGuild()->getName()) }}</a></li>
                            @else
                                <li class="list-group-item col-12 col-md-9 text-center">Ainda não está em uma guild.</li>
                            @endif

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Balanço:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ $account->getPremiumPoints() }} {{ config("otserver.pagseguro.productName") }}</li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Último login:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ $player->getLastLogin() > 0 ? \Carbon\Carbon::createFromTimestamp($player->getLastLogin())->isoFormat('A, d [de] MMMM [de] Y') : _("Nunca efetuou login.") }}</li>

                            <li class="list-group-item col-12 col-md-3 fw-bold text-center">Criada:</li>
                            <li class="list-group-item col-12 col-md-9 text-center">{{ \Carbon\Carbon::createFromTimestamp($player->getCreated())->isoFormat('A, d [de] MMMM [de] Y')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-title">Skills:</div>
            <div class="main-content p-0">
                <div class="row m-0">
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/level.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Level</p>
                            <p class="m-0">{{ $player->getLevel() }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/magic_level.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">ML</p>
                            <p class="m-0">{{ $player->getMagLevel() }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/fist.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Fist</p>
                            <p class="m-0">{{ $player->getSkill(0) }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/club.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Club</p>
                            <p class="m-0">{{ $player->getSkill(1) }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/sword.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Sword</p>
                            <p class="m-0">{{ $player->getSkill(2) }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/axe.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Axe</p>
                            <p class="m-0">{{ $player->getSkill(3) }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/distance.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Dist</p>
                            <p class="m-0">{{ $player->getSkill(4) }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/shield.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Shield</p>
                            <p class="m-0">{{ $player->getSkill(5) }}</p>
                        </div>
                    </div>
                    <div class="col p-0">
                        <a href="" class="d-flex justify-content-center align-items-center my-2">
                            <img src="{{ asset('images/skills/fishing.gif') }}" alt="" style="border-style: none"/>
                        </a>
                        <div class="text-center bg-secondary bg-gradient">
                            <p class="m-0 fw-bold">Fish</p>
                            <p class="m-0">{{ $player->getSkill(6) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @if(Website::getQuests()->count() > 0)
            <div class="bg-title">Quests:</div>
            <div class="main-content p-0">
                <ul class="list-group list-group-horizontal row m-0">
                    @foreach (Website::getQuests()->quests as $quest)
                    <li class="list-group-item col-11">
                        {{ $quest->getName() }}
                    </li>
                    <li class="list-group-item col-1 fw-bold text-center">
                        @if($player->getStorage($quest->getStartStorageId()) === null)
                        <img src="{{ asset('images/general/false.png') }}" alt="" class="img-fluid">
                        @else
                        <img src="{{ asset('images/general/true.png') }}" alt="" class="img-fluid">
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="bg-title">Mortes:</div>
            <div class="main-content p-0">
                <ul class="list-group list-group-horizontal row m-0">
                    @foreach ($player_deaths as $death)
                        <li class="list-group-item col-6">
                            {{ \Carbon\Carbon::createFromTimestamp($death->getDate())->isoFormat('A, d [de] MMMM [de] Y')}}
                        </li>
                        <li class="list-group-item col-6 fw-bold text-center">
                            @foreach ($death->loadKillers() as $killer)
                                @if($loop->iteration == 1)
                                    @if($loop->count<= 4)
                                        Morto no level <b>{{ $death->getLevel() }}</b> para
                                    @elseif($loop->count > 4 and $loop->count < 10)
                                        slain no level <b>{{ $death->getLevel() }}</b> para
                                    @elseif ($loop->count > 9 and $loop->count< 15)
                                        crushed no level <b>{{ $death->getLevel() }}</b> para
                                    @elseif ($loop->count > 14 and $loop->count < 20)
                                        eliminated no level <b>{{ $death->getLevel() }}</b> para
                                    @elseif ($loop->count > 19)
                                        annihilated no level <b>{{ $death->getLevel() }}</b> para
                                    @endif
                                @elseif ($loop->iteration == $loop->count)
                                    {{ _(' e ') }}
                                @else
                                    {{ _(', ') }}
                                @endif

                                @if($killer['player_name'] != "")
                                    @if ($killer['monster_name'] != "")
                                        {{ htmlspecialchars($killer['monster_name']) . " convocado pelo " }}
                                    @endif
                                    @if ($killer['player_exists'] == 0)
                                        <a href="{{ route('characters.index', ['name' => urlencode($killer['player_name'])]) }}">
                                            {{ htmlspecialchars($killer['player_name']) }}
                                        </a>
                                    @endif
                                @else
                                    {{ ucwords(htmlspecialchars($killer['monster_name'])) }}
                                @endif
                            @endforeach
                        </li>
                    @endforeach
                    @if(count($player_deaths) == 0)
                    <li class="list-group-item col-12 text-center">
                        Este personagem não morreu nenhuma vez.
                    </li>
                @endif
                </ul>
            </div>
            @if(!$player->getHideChar())
            <div class="bg-title">Informação da conta:</div>
            <div class="main-content p-0">
                <ul class="list-group list-group-horizontal row m-0">
                    <li class="list-group-item col-3">Real name: </li>
                    <li class="list-group-item col-9 fw-bold text-center">{{ $account->getRLName() }}</li>

                    <li class="list-group-item col-3">Localização: </li>
                    <li class="list-group-item col-9 fw-bold text-center">{{ $account->getLocation() }}</li>

                    <li class="list-group-item col-3">Último login: </li>
                    <li class="list-group-item col-9 fw-bold text-center">
                        @if($account->getLastLogin() > 0)
                            {{ \Carbon\Carbon::createFromTimestamp($player->getLastLogin())->isoFormat('A, d [de] MMMM [de] Y')}}
                        @else
                            {{ _("Nunca efetuou login.") }}
                        @endif
                    </li>
                    <li class="list-group-item col-3">Criada: </li>
                    <li class="list-group-item col-9 fw-bold text-center">
                        @if($account->getCreateDate() > 0)
                            {{ \Carbon\Carbon::createFromTimestamp($player->getCreateDate())->isoFormat('A, d [de] MMMM [de] Y')}}
                        @endif
                    </li>
                    <li class="list-group-item col-3">Status: </li>
                    <li class="list-group-item col-9 fw-bold text-center">
                        {!! $account->isPremium() > 0 ? '<b><font color="green">Conta Premium</font></b>' : '<b><font color="red">Conta Gratis</font></b>' !!}
                    </li>
                    @if($account->isBanned())
                    <li class="list-group-item col-3">Ban: </li>
                    <li class="list-group-item col-9 fw-bold text-center">
                        @if($account->getBanTime() > 0)
                            <span class="text-danger fw-bold">[Banido até {{ date("d M Y, H:i", $account->getBanTime())}}]</span>
                        @else
                            <span class="text-danger fw-bold">[Banido Para Sempre]</span>
                        @endif
                    </li>
                    @endif
                </ul>
            </div>
            @endif
            @if (!$player->getHideChar())
            <div class="bg-title">Personagens:</div>
            <div class="main-content p-0">
                <ul class="list-group list-group-horizontal row m-0">
                    @foreach($account->getPlayersList() as $player_list)
                    <li class="list-group-item col-1 d-flex justify-content-center align-items-center">{{ $loop->iteration }}</li>
                    <li class="list-group-item col-5 fw-bold d-flex justify-content-center align-items-center">
                        <a href="{{ route('characters.index', ['name' => urlencode($player_list->getName())]) }}" class="text-dark fw-bold text-decoration-none">
                            @if($player_list->getOnline() > 0)
                                <span class="text-success fw-bold">{{ htmlspecialchars($player_list->getName()) }}</span>
                            @else
                                <span class="text-danger fw-bold">{{ htmlspecialchars($player_list->getName()) }}</span>
                            @endif
                            <img src="{{ asset(config('otserver.site.flag_images_url'))."/{$account->getFlag()}".config('otserver.site.flag_images_extension') }}" title="Country: {{ $account->getFlag() }}" alt="{{ $account->getFlag() }}" />
                        </a>
                    </li>
                    <li class="list-group-item col-2 fw-bold d-flex justify-content-center align-items-center">{{ config('otserver.site.worlds')[$player_list->getWorld()] }}</li>
                    <li class="list-group-item col-1 fw-bold d-flex justify-content-center align-items-center">{{ $player_list->getLevel() }}</li>
                    <li class="list-group-item col-1 fw-bold d-flex justify-content-center align-items-center">{{ Website::getVocationName($player_list->getVocation()) }}</li>
                    <li class="list-group-item col-1 fw-bold d-flex justify-content-center align-items-center">
                        @if($player_list->isOnline())
                            <span class="text-success fw-bold">Online</span>
                        @else
                            <span class="text-danger fw-bold">Offline</span>
                        @endif
                    </li>
                    <li class="list-group-item col-1 fw-bold d-flex justify-content-center align-items-center">
                        <a href="{{ route('characters.index', ['name' => urlencode($player_list->getName())]) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="bg-title">Procurar Personagem:</div>
            <div class="main-content p-0">
                <form action="{{ route('searchcharacters.redirectWithParams') }}" enctype="multipart/form-data" class="list-group list-group-horizontal row m-0">
                    <li class="list-group-item col-10">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="name">
                            <label for="floatingInput">Procurar</label>
                        </div>
                    </li>
                    <li class="list-group-item col-2 d-flex justify-content-center align-items-center">
                        <button type="submit" class="sbutton-blue float-end">Procurar</button>
                    </li>
                </form>
            </div>
        </div>
    </div>
  </div>
@endsection
