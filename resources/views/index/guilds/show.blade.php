@extends('layout.index')
@section('title', 'Guilds')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Guilds</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Guilds no {{ htmlspecialchars(config('otserver.site.serverName')) }}</div>
            @if($errorsView)
            <div class="main-content">
                @foreach($errors->all() as $message)
                <p class="text-center m-0">{!! $message !!}</p>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12 mt-1">
                    <a href="{{ route('guilds.index') }}" class="sbutton-red mx-auto d-block">Voltar</a>
                </div>
            </div>
            @else
            <div class="main-content">
                <div class="row">
                    <div class="col">
                        <img src="{{ $guild->getGuildLogoLink() }}" width="64" height="64" class="mx-auto d-block">
                    </div>
                    <h1 class="col m-0 d-flex justify-content-center align-items-center" style="font-family: 'Aclonica', sans-serif;">{{ $guild->getName() }}</h1>
                    <div class="col">
                        <img src="{{ $guild->getGuildLogoLink() }}" width="64" height="64" class="mx-auto d-block">
                    </div>
                </div>
                <div class="row">
                    <div class="col-9">
                        <div class="bg-title">Informações da Guild</div>
                        <div class="main-content">
                            <p class="m-0 mb-1">{!! $description !!}</p>
                            <p class="m-0 mb-1"><b><a href="{{ route('characters.index', ['name' => urlencode($guild_owner)]) }}">{{ htmlspecialchars($guild_owner) }}</a></b> é o líder da guild.</p>
                            <p class="m-0">A guild foi fundada no {{ htmlspecialchars(config('otserver.server.serverName')) }} em {{ ucwords(\Carbon\Carbon::createFromTimestamp($guild->getCreationData())->isoFormat('DD [de] MMMM [de] Y')) }}.</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="bg-title">Navegação</div>
                        <div class="main-content">
                            <p class="mb-1"><a href="{{ route('guilds.manager', ['guildId' => $guild->getId()]) }}" class="sbutton-blue mx-auto d-block">Gerenciar Guild</a></p>
                            <p class="mb-1"><a href="{{ route('guilds.index') }}" class="sbutton-red mx-auto d-block">Voltar</a></p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-title">Membros da Guild</div>
                        <div class="main-content p-1">
                            <table class="table table-striped table-bordered align-middle m-0">
                                <thead class="table-dark">
                                    <th class="text-center">Cargo</th>
                                    <th>Nome e título</th>
                                    <th class="text-center">Ação</th>
                                </thead>
                                <tbody>
                                    @foreach ($rank_list as $rank)
                                        @if(count($rank->getPlayersList()) > 0)
                                        <tr>
                                            <td class="text-center">{{ htmlspecialchars($rank->getName()) }}</td>
                                            @foreach($rank->getPlayersList() as $player)
                                                <td class="d-flex align-items-center">
                                                    <a href="{{ route('characters.index', ['name' => urlencode($player->getName())]) }}" class="{{ $player->isOnline() ? 'text-success' : 'text-danger' }}">{{ htmlspecialchars($player->getName()) }}</a>
                                                    @if($logged)
                                                        @if(in_array($player->getId(), $players_from_account_ids))
                                                            <form action="{{ route('guilds.changenick', ['guildId' => $guild->getId(), 'name' => urlencode($player->getName())]) }}" method="POST">
                                                                @csrf
                                                                (<input type="text" name="nick" value="{{ htmlspecialchars($player->getGuildNick()) }}">
                                                                <Button type="submit" class="btn btn-sm btn-outline-secondary ">Trocar</Button>)
                                                            </form>
                                                        @else
                                                            @if(!empty($player->getGuildNick()))
                                                                ({{ htmlspecialchars($player->getGuildNick()) }})
                                                            @endif
                                                        @endif
                                                    @else
                                                        ({{ htmlspecialchars($player->getGuildNick()) }})
                                                    @endif
                                                </td>
                                                @if($level_in_guild > $rank->getLevel() || $guild_leader)
                                                <td class="text-center">
                                                    <a href="{{ route('guilds.kickplayer', ['guildId' => $guild->getId(), 'name' => urlencode($player->getName())]) }}">Dispensar</a>
                                                </td>
                                                @else
                                                <td class="text-center">-</td>
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-title">Personagens convidados</div>
                        <div class="main-content p-1">
                            <table class="table table-striped table-bordered m-0">
                                <thead class="table-dark">
                                    <th>Nome</th>
                                    <th class="text-center">Ação</th>
                                </thead>
                                <tbody>
                                    @if (count($guild->listInvites()) == 0)
                                        <tr>
                                            <td colspan="2" class="text-center">Nenhum personagem convidado encontrado.</td>
                                        </tr>
                                    @else
                                        @foreach ($guild->listInvites() as $invited_player)
                                            @if (count($account_players) > 0)
                                                <tr>
                                                    <td class="col">
                                                        <a href="{{ route('characters.index', ['name' => urlencode($invited_player->getName())]) }}" class="text-dark">{{ htmlspecialchars($invited_player->getName()) }}</a>
                                                    </td>
                                                    <td class="col-2 text-center">
                                                        @if($guild_vice)
                                                            (<a href="{{ route('guilds.deleteinvite', ['guildId' => $guild->getId(), 'name' => urlencode($invited_player->getName())]) }}" class="text-dark">Cancelar convite</a>)
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-title">Ações</div>
                        <div class="main-content d-flex justify-content-center">
                            @if(!$logged)
                                <a href="{{ route('accountmanagement.login') }}" class="sbutton-blue">Login</a>
                            @else
                                @if($show_accept_invite > 0)
                                    <a href="{{ route('guilds.acceptinvite', ['guildId' => $guild->getId()]) }}" class="sbutton-blue">Aceitar convite</a>
                                @endif
                                @if($guild_vice)
                                    <a href="{{ route('guilds.invite', ['guildId' => $guild->getId()]) }}" class="sbutton-blue">Convidar jogador</a>
                                    <a href="{{ route('guilds.changerank', ['guildId' => $guild->getId()]) }}" class="sbutton-blue">Alterar rank</a>
                                @endif
                                @if($players_from_account_in_guild > 0)
                                    <a href="{{ route('guilds.leaveguild', ['guildId' => $guild->getId()]) }}" class="sbutton-red">Deixar guild</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
  </div>
@endsection
