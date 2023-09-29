@extends('layout.index')
@section('title', 'LostAccount')
@push('scripts')
<script>
    $('#submit').click(function(){
        $('#form').submit();
    })
</script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>LostAccount</span>
    </div>
    <div class="bg-content" style="padding-top: 26px;padding-bottom: 26px;">
        <div class="container">
            <div class="bg-title">Recuperar Conta Perdida</div>
            @if($errorsView)
                <div class="main-content">
                    <div class="row d-flex justify-content-center">
                        <div class="col-6">
                            @foreach($errors->all() as $message)
                            <p class="text-center m-0">{!! $message !!}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="{{ route('lostaccount.index') }}" class="sbutton-blue mt-2 d-block mx-auto">Voltar</a>
            @else
            <div class="main-content">
                <div class="row d-felx justify-content-center">
                    <div class="col-6">
                        @if($emptyView)
                            <p>Por favor, insira o código do e-mail e o nome de um personagem da conta. Em seguida, pressione Enviar.</p>
                            <form id="form" action="{{ route('lostaccount.checkcode') }}" method="post">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="code" name="code" placeholder="Por favor insira o código">
                                    <label for="code">Seu código</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="character" name="character" placeholder="Por favor insira o nome do seu personagem">
                                    <label for="character">Personagem</label>
                                </div>
                            </form>
                        @else
                            <p>Por favor, digite a nova senha em sua conta e repita para ter certeza de lembrar a senha.</p>
                            <form id="form" action="{{ route('lostaccount.setnewpassword') }}" method="post">
                                @csrf
                                <input type="hidden" name="code" value="{{ htmlspecialchars($character) }}">
                                <input type="hidden" name="character" value="{{ htmlspecialchars($code) }}">

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="passor" name="passor" placeholder="Nova senha">
                                    <label for="passor">Nova senha</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="passor2" name="passor2" placeholder="Repita a nova senha">
                                    <label for="passor2">Repita a nova senha</label>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <button id="submit" type="submit" class="sbutton-blue mt-2 d-block mx-auto">Enviar</button>
            @endif
        </div>
    </div>
</div>
@endsection
