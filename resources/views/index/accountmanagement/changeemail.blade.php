@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            @if($account_email_new_time < 10)
                @if($success)
                    <div class="bg-title">Novo e-mail solicitado</div>
                    <div class="main-content">
                        Você pediu para mudar seu endereço de e-mail para <strong>{{ $account_email_new }}</strong>. A mudança será realizada após <strong>{{ date("d M Y, H:i:s", $account_email_new_time) }}</strong>, durante o qual você pode cancelar o pedido a qualquer momento.
                    </div>
                    <div class="row">
                        <div class="col-12 mt-1">
                            <a href="{{ route('accountmanagement.index') }}" class="sbutton-red">Voltar</a>
                        </div>
                    </div>
                @else
                    <div class="bg-title">Alterar endereço de email</div>
                    <div class="main-content">
                        @foreach($errors->all() as $message)
                            <x-notification message="{{ $message }}" isAutoClose="false" class="mt-3"/>
                        @endforeach

                        <p class="mb-2 text-center">Por favor, digite a sua senha e endereço de e-mail novo. Certifique-se de que você digite um endereço de email válido que você tem acesso. Por razões de segurança, a mudança real será finalizado após um período de espera de 2 dias. Por razões de segurança, a mudança real será finalizado após um período de espera de {{ config('otserver.site.email_days_to_change') }} dias.</p>
                        <form id="submitForm" action="changeemail" method="post" class="col-5 mx-auto">
                            @csrf
                            <input type="hidden" name="changeemailsave" value="1">

                            <div class="form-floating mb-1">
                                <input type="email" class="form-control" id="new_email" placeholder="Novo E-mail:"
                                name="new_email" maxlength="50">
                                <label for="new_email">Novo E-mail:</label>
                            </div>
                            <div class="form-floating mb-1">
                                <input type="password" class="form-control" id="password" placeholder="Senha:"
                                name="password" maxlength="29">
                                <label for="password">Senha:</label>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-1">
                            <button type="submit" class="sbutton-blue" onclick="submitForm()">Alterar Email</button>
                            <a href="{{ route('accountmanagement.index') }}" class="sbutton-red">Voltar</a>
                        </div>
                    </div>
                @endif
            @else
                @if($account_email_new_time < time())
                    @if($changeemailsave == 1)
                        <div class="bg-title">Alteração de email</div>
                        <div class="main-content">
                            <p class="mb-2 text-center">Você aceitou {{ $account_logged->getEmail() }} como o seu novo endereço de e-mail.</p>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-1">
                                <a href="{{ route('accountmanagement.index') }}" class="sbutton-red">Voltar</a>
                            </div>
                        </div>
                    @else
                    <div class="bg-title">Alterar endereço de email</div>
                    <div class="main-content">
                        <p class="mb-2 text-center">Por favor, digite a sua senha e endereço de e-mail novo. Certifique-se de que você digite um endereço de email válido que você tem acesso. Por razões de segurança, a mudança real será finalizado após um período de espera de 2 dias. Por razões de segurança, a mudança real será finalizado após um período de espera de {{ config('otserver.site.email_days_to_change') }} dias.</p>
                        <form id="confirmForm" action="changeemail" method="post" class="col-5 mx-auto">
                            @csrf
                            <input type="hidden" name="changeemailsave" value="1">
                        </form>
                        <form id="cancelForm" action="changeemail" method="post" class="col-5 mx-auto">
                            @csrf
                            <input type="hidden" name="emailchangecancel" value="1">
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-1">
                            <button type="submit" class="sbutton-blue" onclick="confirmForm()">Confirmar</button>
                            <button type="submit" class="sbutton-blue" onclick="cancelForm()">Cancelar</button>
                            <a href="{{ route('accountmanagement.index') }}" class="sbutton-red">Voltar</a>
                        </div>
                    </div>
                    @endif
                @else
                @if(@$emailchangecancel == 1)
                <div class="bg-title">Alterar endereço de email</div>
                <div class="main-content">
                    <p class="mb-2 text-center">Sua solicitação para alterar o endereço de e-mail da sua conta foi cancelada. O endereço de e-mail não será alterado.</p>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="{{ route('accountmanagement.index') }}" class="sbutton-red">Voltar</a>
                    </div>
                </div>
                @else
                <div class="bg-title">Alterar endereço de email</div>
                <div class="main-content">
                    <p class="mb-2 text-center">Um pedido foi enviado para alterar o endereço de e-mail desta conta para <strong><?= $account_email_new ?></strong>.<br />A mudança será realizada em <strong><?= date("j F Y, G:i:s", $account_email_new_time) ?></strong>.<br>Se você não quiser alterar o seu endereço de e-mail, por favor clique em "Cancelar".</p>
                    <form id="cancelForm" action="changeemail" method="post" class="col-5 mx-auto">
                        @csrf
                        <input type="hidden" name="emailchangecancel" value="1">
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button type="submit" class="sbutton-blue" onclick="cancelForm()">Cancelar</button>
                        <a href="{{ route('accountmanagement.index') }}" class="sbutton-red">Voltar</a>
                    </div>
                </div>
                @endif
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

        const confirmForm = () => {
            document.getElementById("confirmForm").submit();
        }

        const cancelForm = () => {
            document.getElementById("cancelForm").submit();
        }
    </script>
@endpush
