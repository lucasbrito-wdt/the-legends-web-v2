@extends('layout.index')
@section('title', 'Guilds')
@push('scripts')
<script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
    </script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Guilds</span>
    </div>
    <div class="bg-content pb-1">
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
                    <div class="col d-flex align-items-center">
                        <img src="{{ asset("images/guilds/default_guild_logo.gif") }}" width="64" height="64" class="img-fluid mx-auto d-block">
                    </div>
                    <h1 class="col-8 m-0 d-flex justify-content-center align-items-center text-center" style="font-family: 'Aclonica', sans-serif;">Bem-vindo ao Gerenciamento da Guild !</h1>
                    <div class="col d-flex align-items-center">
                        <img src="{{ asset("images/guilds/default_guild_logo.gif") }}" width="64" height="64" class="img-fluid mx-auto d-block">
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <p class="text-center m-0 mt-4 fw-bold">Aqui você pode mudar os nomes de todas as classes, apagar e adicionar fileiras, passar a liderança para outro membro da guild e excluir guild.</p>

                        <div class="bg-title">Navegação</div>
                        <div class="main-content p-1">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle text-center">
                                    <thead class="table-dark">
                                        <th>Opções</th>
                                        <th>Descrições</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="170"><b><a href="{{ route('guilds.passleadership', ['guildId' => $guild->getId()]) }}" class="text-dark">Passa Liderança</a></b></td>
                                            <td><b>Passa liderança da guild para outro membro da guild.</b></td>
                                        </tr>
                                        <tr>
                                            <td width="170"><b><a href="{{ route('guilds.deleteguild', ['guildId' => $guild->getId()]) }}" class="text-dark">Deletar Guild</a></b></td>
                                            <td><b>Deletar sua guild e expulsar todos os membros dela.</b></td>
                                        </tr>
                                        <tr>
                                            <td width="170"><b><a href="{{ route('guilds.changedescription', ['guildId' => $guild->getId()]) }}" class="text-dark">Mudar Descrição</a></b></td>
                                            <td><b>Alterar descrição da guild.</b></td>
                                        </tr>
                                        <tr>
                                            <td width="170"><b><a href="{{ route('guilds.changemotd', ['guildId' => $guild->getId()]) }}" class="text-dark">Mudar MOTD</a></b></td>
                                            <td><b>Mudar messagem da guild.</b></td>
                                        </tr>
                                        <tr>
                                            <td width="170"><b><a href="{{ route('guilds.changelogo', ['guildId' => $guild->getId()]) }}" class="text-dark">Mudar Logo</a></b></td>
                                            <td><b>Faça upload do novo logotipo da guild.</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-title d-flex align-items-center">
                            <div class="col-11">Alterar nomes de classificação e níveis</div>
                            <div class="col-1 text-end">
                                <a href="{{ route('guilds.addrank', ['guildId' => $guild->getId()]) }}" class="text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar rank"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="main-content p-1">
                            <form action="{{ route('guilds.saveranks', ['guildId' => $guild->getId()]) }}" method="POST">
                                @csrf
                                <table class="table table-bordered table-striped align-middle text-center m-0">
                                    <thead>
                                        <th>Descrição</th>
                                        <th class="bg-danger text-white">Lider (3)</th>
                                        <th class="bg-warning text-white">Vice (2)</th>
                                        <th class="bg-success text-white">Membro (1)</th>
                                        <th class="bg-dark text-white" colspan="2"><i class="fa fa-cogs"></i></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($rank_list as $rank)
                                        <tr>
                                            <td class="col-5"><input type="text" name="{{ $rank->getId() }}_name" value="{{ $rank->getName() }}" class="form-control"></td>
                                            <td class="col-2">
                                                <div class="form-check">
                                                    <input class="form-check-input float-none" type="radio" name="{{$rank->getId()}}_level" id="{{$rank->getId()}}_level" value="3" @if($rank->getLevel() == 3) checked @endif>
                                                </div>
                                            </td>
                                            <td class="col-2">
                                                <div class="form-check">
                                                    <input class="form-check-input float-none" type="radio" name="{{$rank->getId()}}_level" id="{{$rank->getId()}}_level" value="2" @if($rank->getLevel() == 2) checked @endif>
                                                </div>
                                            </td>
                                            <td class="col-2">
                                                <div class="form-check">
                                                    <input class="form-check-input float-none" type="radio" name="{{$rank->getId()}}_level" id="{{$rank->getId()}}_level" value="1" @if($rank->getLevel() == 1) checked @endif>
                                                </div>
                                            </td>
                                            <td class="col-1">
                                                <a href="{{ route('guilds.deleterank', ['guildId' => $guild->getId(), 'rankId' => $rank->getId()]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Deletar Rank" class="text-dark"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <button type="submit" class="sbutton-blue">Salvar Tudo</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>

                        <div class="bg-title">Informação de classificação</div>
                        <div class="main-content p-2">
                            <ul class="list-unstyled">
                                <li>0. Dono da guild - é a classificação mais alta, apenas um jogador na guild pode ter essa classificação. O jogador com esta classificação pode:</li>
                                <ul>
                                    <li>Convidar / Cancelar convite / Expulsar jogador da guild</li>
                                    <li>Mude as classificações de todos os jogadores na guild</li>
                                    <li>Exluír a guild ou passe a liderança para outro membro da guild</li>
                                    <li>Mudar nomes, níveis (líder, vice, membro), adicionar e excluir classificações</li>
                                    <li>Alterar MOTD, logotipo e descrição da guild</li>
                                </ul>
                            </ul>
                            <hr>
                            <ul class="list-unstyled m-0">
                                <li>1. Membro - é a classificação mais baixa na guild. O jogador com esta classificação pode:</li>
                                <ul>
                                    <li>Seja um membro da guild</li>
                                </ul>
                            </ul>
                            <hr>
                            <ul class="list-unstyled">
                                <li>2. Vice líder - é a terceira posição na guild. O jogador com esta classificação pode:</li>
                                <ul>
                                    <li>Convidar / cancelar convite</li>
                                    <li>Alterar a classificação dos jogadores com nível de classificação inferior ("membro") na guild</li>
                                </ul>
                            </ul>
                            <hr>
                            <ul class="list-unstyled">
                                <li>3. Líder - é o segundo posto na guild. O jogador com esta classificação pode:</li>
                                <ul>
                                    <li>Convidar / Cancelar convite / Expulsar jogador da guild</li>
                                    <li>Mude as classificações de todos os jogadores na guild</li>
                                </ul>
                            </ul>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="d-flex justify-content-center mt-2">
            <a href="{{ route('guilds.show', ['guildId' => $guild->getId()]) }}" class="sbutton-red">Voltar</a>
        </div>
    </div>
  </div>
@endsection
