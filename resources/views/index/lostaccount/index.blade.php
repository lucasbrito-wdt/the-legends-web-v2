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
            <div class="main-content">
                <div class="row d-flex justify-content-center">
                    <div class="col-6">
                        <p class="mx-block text-center">A interface de conta perdida pode ajudá-lo a recuperar seu nome de conta e senha. Por favor, insira o nome do seu personagem e selecione o que você deseja fazer.</p>
                        <form id="form" action="{{ route('lostaccount.step1') }}" method="POST">
                            @csrf
                            <input type="hidden" name="character">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nick" name="nick" placeholder="Por favor insira o nome do seu personagem">
                                <label for="nick">Por favor insira o nome do seu personagem</label>
                            </div>
                            <p class="mb-0">O que você quer?</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="action_type" id="email" value="email" checked>
                                <label class="form-check-label" for="email">Envie-me uma nova senha e meu nome de conta para o endereço de e-mail da conta.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="action_type" id="reckey" value="reckey">
                                <label class="form-check-label" for="reckey">Recebi a <b>chave de recuperação</b> e desejo definir uma nova senha e endereço de e-mail para minha conta.</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <button id="submit" class="sbutton-blue mt-2 d-block mx-auto">Recuperar</button>
        </div>
    </div>
</div>
@endsection
