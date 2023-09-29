@extends('layout.index')
@section('title', 'Guilds')
@push('scripts')
<script>
    $('#submitGuild').click(function(){
        $('#formGuild').submit();
    })
</script>
@endpush
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
            <div class="main-content mb-2">
                @error('check_array_of_player_nig')
                    <x-notification message="{!! $message !!}" isAutoClose="false" class="mb-3"/>
                @enderror

                <p>Para jogar no {{ htmlspecialchars(config('otserver.site.serverName')) }} você precisa de uma conta.</p>
                <p>Tudo que você precisa fazer para criar sua nova conta é inserir seu endereço de e-mail, senha da nova conta, código de verificação da foto e concordar com os termos apresentados abaixo.</p>
                <p>Caso tenha feito isso, seu número de conta, senha e endereço de e-mail serão mostrados na página a seguir e sua conta e senha serão enviados para seu endereço de e-mail junto com mais instruções.</p>

                <form id="formGuild" action="{{ route('guilds.createguild') }}" method="POST" class="row d-flex flex-column align-items-center">
                    @csrf
                    <div class="col-5">
                        <div class="form-floating mb-1">
                            <select class="form-select @error('new_guild_player') is-invalid @enderror" id="new_guild_player" name="new_guild_player" aria-label="Selecione um líder">
                              <option selected>Selecione um líder</option>
                              @if(count($array_of_player_nig) > 0)
                                @foreach($array_of_player_nig as $name)
                                    <option value="{{ urlencode($name) }}">{{ htmlspecialchars($name) }}</option>
                                @endforeach
                              @endif
                            </select>
                            <label for="new_guild_player">Lider:</label>
                            @error('new_guild_player')
                                <div class="help-block @error('new_guild_player') invalid-feedback @enderror">{!! $message !!}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="new_guild_name" class="form-control @error('new_guild_name') is-invalid @enderror" id="new_guild_name" placeholder="Nome da Guild:">
                            <label for="new_guild_name">Nome da Guild:</label>
                            @error('new_guild_name')
                                <div class="help-block @error('new_guild_name') invalid-feedback @enderror">{!! $message !!}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-center">
                <button id="submitGuild" type="submit" class="sbutton-blue">Criar Guild</button>
                <a href="{{ route('guilds.index') }}" class="sbutton-red">Cancelar</a>
            </div>
            @endif
        </div>
    </div>
  </div>
@endsection
