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
                @if(empty($actionType))
                    <div class="main-content">
                        <div class="row d-flex justify-content-center">
                            <div class="col-6">
                                <p>Selecione a ação, <a href="{{ route('lostaccount.index') }}">Voltar</a></p>
                            </div>
                        </div>
                    </div>
                @elseif($actionType == "email")
                    @if($viewEmail)
                        <div class="main-content">
                            <div class="row d-flex justify-content-center">
                                <div class="col-6">
                                    <p class="mx-block text-center">Por favor, insira o e-mail para conta com este personagem.</p>
                                    <form id="form" action="{{ route('lostaccount.sendcode') }}" method="POST">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nick" placeholder="Por favor insira o nome do seu personagem" value="{{ htmlspecialchars($nick) }}" readonly="readonly">
                                            <label for="nick">Por favor insira o nome do seu personagem</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="email" placeholder="Por favor, insira o e-mail da conta">
                                            <label for="email">Por favor, insira o e-mail da conta</label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <button id="submit" class="sbutton-blue mt-2 d-block mx-auto">Recuperar</button>
                    @else
                        <div class="main-content">
                            <div class="row d-flex justify-content-center">
                                <div class="col-6">
                                    {!! $viewEmailContent !!}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('lostaccount.index') }}" class="sbutton-blue mt-2 d-block mx-auto">Voltar</a>
                    @endif
                @elseif($actionType == "reckey")
                    @if($viewReckey)
                        <div class="main-content">
                            <div class="row d-flex justify-content-center">
                                <div class="col-6">
                                    <p class="mx-block text-center">Se você inserir a chave de recuperação correta, verá um formulário para definir um novo e-mail e senha para a conta. Para este e-mail serão enviados sua nova senha e nome de conta..</p>
                                    <form id="form" action="{{ route('lostaccount.step2') }}" method="POST">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nick" placeholder="Por favor insira o nome do seu personagem" name="nick" value="{{ htmlspecialchars($nick) }}" readonly="readonly">
                                            <label for="nick">Por favor insira o nome do seu personagem</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="key" name="key" placeholder="Recovery key">
                                            <label for="key">Recovery key</label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <button id="submit" class="sbutton-blue mt-2 d-block mx-auto">Recuperar</button>
                    @else
                    <div class="main-content">
                        <div class="row d-flex justify-content-center">
                            <div class="col-6">
                                {!! $viewReckeyContent !!}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('lostaccount.index') }}" class="sbutton-blue mt-2 d-block mx-auto">Voltar</a>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
