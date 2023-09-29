@extends('layout.index')
@section('title', 'Conta')
@push('scripts')
<script type="text/javascript">
function showResult(str) {
  if (str.length == 0) {
    document.getElementById("name_check").innerHTML = "Digite o nome do seu personagem.";
    return;
  }

  let xmlhttp=new XMLHttpRequest();

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        let response = JSON.parse(this.response);
        document.getElementById("name_check").innerHTML = response.message;
        if(response.code == 200)
            document.getElementById("submit").removeAttribute("disabled");
        if(response.code == 400)
            document.getElementById("submit").setAttribute("disabled","disabled");
    }
  }

  xmlhttp.open("GET","/checkname/"+ encodeURIComponent(str) ,true);
  xmlhttp.setRequestHeader('Content-Type', 'application/json');
  xmlhttp.send();
}
</script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Criar Personagem</div>
            @if($success)
                <div class="main-content">
                    @foreach($errors->all() as $message)
                    <p class="text-center m-0">{!! $message !!}</p>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
            @else
                <div class="main-content">
                    <p class="mb-2 text-center">Aqui você pode criar seu personagem escolhendo o nome @if (count(config('otserver.site.newchar_vocations')[$world]) > 1), vocação @endif, o sexo para o seu personagem. O nome não deve violar as convenções de nomenclatura declarados nas regas do <a href="" target="_blank">{{ config('otserver.server.serverName') }}</a>, ou o seu personagem pode ser banido, nome bloqueado ou excluído.</p>
                    @if($account_logged->getPlayersList()->count() >= config('otserver.site.max_players_per_account'))
                        <p class="mb-2 text-center text-danger">Você tem o número máximo de personagens por conta. Você pode excluir um personagem antes de criar um novo.</p>
                    @endif
                    @foreach($errors->all() as $message)
                        <x-notification message="{!! $message !!}" isAutoClose="false" class="mt-3 mb-3"/>
                    @endforeach
                    <form id="submitForm" action="{{ route('accountmanagement.createcharacter', ['world' => $world]) }}" method="post" class="col-8 mx-auto">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating mb-1">
                                    <input type="text" name="newchar_name" class="form-control" id="newchar_name" placeholder="Nome:" maxlength="29" onkeyup="showResult(this.value)">
                                    <label for="newchar_name">Nome:</label>
                                    <p id="name_check" class="help-block" style="font-family:verdana,arial,helvetica; font-size:10px;">
                                        Digite o nome do seu personagem.
                                    </p>
                                </div>

                                @if(count(config('otserver.site.newchar_vocations')[$world]) > 0)
                                <div class="mb-1" style="height: 74px;">
                                    <p class="mb-1">Vocação:</p>

                                    @foreach (config('otserver.site.newchar_vocations')[$world] as $char_vocation_key => $sample_char)
                                    <div class="form-check form-check-inline" style="height: 30px;">
                                        <input class="form-check-input" type="radio" name="newchar_vocation" id="newcharvocation-{{ $char_vocation_key }}" value="{{ $char_vocation_key }}" @if ($loop->first) checked @endif>
                                        <label class="form-check-label" for="newcharvocation-{{ $char_vocation_key }}">{{ $sample_char }}</label>
                                    </div>
                                    @endforeach

                                    <p class="help-block" style="font-family:verdana,arial,helvetica; font-size:10px;">
                                        Selecione a vocação do seu personagem.
                                    </p>
                                </div>
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="mb-3" style="height: 74px;">
                                    <p class="mb-1">Sexo:</p>
                                    <div class="form-check form-check-inline" style="height: 30px;">
                                        <input class="form-check-input" type="radio" name="newchar_sex" id="newchar_sex-1" value="1" checked>
                                        <label class="form-check-label" for="newchar_sex-1">Homem</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="newchar_sex" id="newchar_sex-0" value="0">
                                        <label class="form-check-label" for="newchar_sex-0">Mulher</label>
                                    </div>
                                    <p class="help-block" style="font-family:verdana,arial,helvetica; font-size:10px;">
                                        Selecione o sexo do seu personagem.
                                    </p>
                                </div>
                                @if(count(config('otserver.site.newchar_towns')[$world]) > 0)
                                <div class="mb-1" style="height: 74px;">
                                    <p class="mb-1">Cidade:</p>
                                    @foreach(config('otserver.site.newchar_towns')[$world] as $town_id => $town_nome)
                                    <div class="form-check form-check-inline" style="height: 30px;">
                                        <input class="form-check-input" type="radio" name="newchar_town" id="newchartown-{{$town_id}}" value="{{ $town_id }}" @if ($loop->first) checked @endif>
                                        <label class="form-check-label" for="newchartown-{{$town_id}}">{{ $town_nome }}</label>
                                    </div>
                                    @endforeach
                                    <p class="help-block" style="font-family:verdana,arial,helvetica; font-size:10px;">
                                        Selecione sua cidade do seu personagem.
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button id="submit" type="submit" class="sbutton-blue" onclick="submitForm()">Criar Personagem</button>
                        <a href="{{ route('accountmanagement.index') }}" class="sbutton-red">Voltar</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        const submitForm = () => {
            document.getElementById("submitForm").submit();
        }
    </script>
@endpush
