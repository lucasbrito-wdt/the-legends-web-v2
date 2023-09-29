@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Editar Informações do Personagem</div>
            @if($success)
                <div class="main-content">
                    @foreach($errors->all() as $message)
                    <p class="text-center m-0">{{ $message }}</p>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
            @else
                <div class="main-content">
                    <p class="mb-2 text-center">Aqui você pode dizer outros jogadores sobre si mesmo. Esta informação será apresentada juntamente com os dados de seus personagens. Se você não quiser preencher um determinado campo, apenas deixá-lo em branco.</p>
                    @foreach($errors->all() as $message)
                        <x-notification message="{{ $message }}" isAutoClose="false" class="mt-3 mb-3"/>
                    @endforeach
                    <form id="submitForm" action="{{ route('accountmanagement.changecomment', ['name' => $player_name]) }}" method="post" class="col-5 mx-auto">
                        @csrf
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" id="name" placeholder="Nome Completo:" maxlength="50" value="{{ $player_name }}" disabled>
                            <label for="name">Nome:</label>
                        </div>
                        <div class="my-1">
                            <p class="mb-1">Ocultar Personagem:</p>
                                <div class="form-check">
                                <input class="form-check-input" type="radio" name="new_hideacc" id="new_hideacc-option-1" @if($player->getHideChar() == 1) checked @endif value="1">
                                <label class="form-check-label" for="new_hideacc-option-1">
                                    Marque para ocultar as informações do personagem
                                </label>
                                </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="new_hideacc" id="new_hideacc-option-2" @if($player->getHideChar() == 0) checked @endif value="0">
                                <label class="form-check-label" for="new_hideacc-option-2">
                                    Desmarque para mostrar as informações do personagem
                                </label>
                            </div>
                        </div>
                        <div class="form-floating">
                            <textarea name="comment" class="form-control" placeholder="Comentário" id="comment" maxlength="2000" style="height: 100px">{{ $player->getComment() }}</textarea>
                            <label for="comment">Comentário</label>
                            <span class="help-block">
                                [max: 2000 caracteres, 50 linhas]
                            </span>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button type="submit" class="sbutton-blue" onclick="submitForm()">Editar Informações</button>
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
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
