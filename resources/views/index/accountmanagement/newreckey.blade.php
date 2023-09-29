@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Gerar chave de recuperação</div>
            @if(config('otserver.site.generate_new_reckey') && empty($reckey))
                <div class="main-content">
                    <p class="text-center m-0">Você não pode obter nova chave de recuperação</p>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
            @else
                @if($success)
                <div class="main-content">
                    <p>Obrigado por registrar sua conta! Você agora pode recuperar a sua conta se você perdeu o acesso ao endereço de e-mail atribuído pelo uso da seguinte.</p>
                    <p>
                        <blockquote><strong style="font-size: x-large;">Codigo: {{ $reckey }}</strong></blockquote>
                    </p>
                    <strong>Importante:</strong>
                    <ul>
                        <li>A note essa chave de recuperação cuidadosamente.</li>
                        <li>Guarde-o em um lugar seguro!</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
                @else
                <div class="main-content">
                    @foreach($errors->all() as $message)
                        <x-notification message="{{ $message }}" isAutoClose="false" class="mt-3 mb-3"/>
                    @endforeach
                    <p class="text-center">Você precisa de <b>{{ config('otserver.site.generate_new_reckey_price') }} {{ config('otserver.pagseguro.productName') }}</b> para gerar uma nova chave de recuperação. Você tem <b>{{ $points }}<b> {{ config('otserver.pagseguro.productName') }}.</p>
                    <form id="submitForm" action="newreckey" method="POST" class="col-5 mx-auto">
                        @csrf
                        <div class="form-floating mb-1">
                            <input type="reg_password" class="form-control" id="reg_password" placeholder="Senha:"
                            name="reg_password" maxlength="29" @if($points <= 0) disabled @endif>
                            <label for="password">Senha:</label>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button type="submit" class="sbutton-blue" onclick="submitForm()">Gerar Chave</button>
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
                @endif
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
