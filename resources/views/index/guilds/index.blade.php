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
            <div class="main-content">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <th>*</th>
                        <th>Logo</th>
                        <th>Description</th>
                        <th class="text-center"><i class="fas fa-cogs"></i></th>
                    </thead>
                    <tbody>
                        @if(count($guilds_list) > 0)
                            @foreach($guilds_list as $guild)
                                @php
                                    $description = $guild->getDescription();
                                    $newlines = array("\r\n", "\n", "\r");
                                    $description_with_lines = str_replace($newlines, '<br />', $description, $count);
                                    if ($count < config('otserver.site.guild_description_lines_limit'))
                                        $description = $description_with_lines;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="col-1"><img src="{{ $guild->getGuildLogoLink() }}" alt="{{ $guild->getName() }}" width="64" height="64" class="mx-auto d-block"></td>
                                    <td class="col-10">{{ $guild->getName() }}</td>
                                    <td class="col">
                                        <a href="{{ route('guilds.show', ['guildId' => $guild->getId()]) }}" class="sbutton-blue">Ver</a>
                                        @if(Visitor::getAccount()->getPageAccess() >= config('otserver.site.access_admin_panel'))
                                        <a href="{{ route('guilds.deletebyadmin', ['guildId' => $guild->getId()]) }}" class="sbutton-red">Delete</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>1</td>
                                <td class="col-1"><img src="{{ asset("images/guilds/default_guild_logo.gif") }}" width="64" height="64" class="img-fluid mx-auto d-block"></td>
                                <td class="col-10"><b>Criar guild</b><p class="m-0">Atualmente não há guild no servidor. Crie primeiro! Pressione o botão "Criar Guild".</p></td>
                                <td class="col"><a href="{{ route('guilds.createguild') }}" class="sbutton-blue">Criar Guild</a></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if(Visitor::isLogged())
                    <h5>Sem aliança descobriu que se adapte às suas necessidades?</h5>
                    <a href="{{ route('guilds.createguild') }}" class="sbutton-blue">Crirar Guild</a>
                @else
                    <h5>Antes de criar uma guild, você deve fazer o login.</h5>
                    <a href="{{ route('accountmanagement.login', ['redirect' => 'guilds']) }}" class="sbutton-blue">Login</a>
                @endif
            </div>
        </div>
    </div>
  </div>
@endsection
