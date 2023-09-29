@extends('layout.index')
@section('title', 'LostAccount')
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
                        <p>A nova senha para sua conta está abaixo. Agora você pode fazer o login.</p>
                        <div class="row">
                            <div class="col-6">Nome da conta</div>
                            <div class="col-6">{{ htmlspecialchars($account->getName()) }}</div>
                            <div class="col-6">Nova senha</div>
                            <div class="col-6">{{ htmlspecialchars($newpassword) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <button id="submit" type="submit" class="sbutton-blue mt-2 d-block mx-auto">Enviar</button>
            @endif
        </div>
    </div>
</div>
@endsection
