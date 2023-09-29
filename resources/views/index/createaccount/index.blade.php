@extends('layout.index')
@section('title', 'Conta')
@push('scripts')
<script src='https://www.google.com/recaptcha/api.js?render={{ config('captcha.sitekey') }}'></script>
<script type="text/javascript">
let accountHttp, emailHttp;

const geolocation = () => {
    axios.get("http://ip-api.com/json").then(response => {
        console.log(response)
        $("#account_location").val(`${response.data.city}, ${response.data.regionName}, ${response.data.country}`)
        $("#account_flag").val(response.data.countryCode)
        $("#account_ip").val(response.data.query)
    });
}

const getRules = () => {
    axios.get("/rules").then(response => {
       let html = $.parseHTML(response.data)
       body = $(html).find("#rules")
      $("#rules").text(body.text());
    });
}

geolocation();
getRules();

const checkAccount = () =>
{
	if(document.getElementById("account_name").value == "")
	{
		document.getElementById("acc_name_check").innerHTML = '(Por favor, entre com seu novo número da conta)';
		return;
	}
	accountHttp = new XMLHttpRequest();
    if (accountHttp == null) return;

	let account = document.getElementById("account_name").value;
    let url = "/checkaccountname/" + account;

	accountHttp.onreadystatechange = AccountStateChanged;
	accountHttp.open("GET", url, true);
	accountHttp.send(null);
}

const checkEmail = () =>
{
	if(document.getElementById("account_email").value=="")
	{
		document.getElementById("account_email").innerHTML = '(O seu endereso de e-mail é necessário)';
		return;
    }

    emailHttp = new XMLHttpRequest();

	if (emailHttp == null) return;

	let email = document.getElementById("account_email").value;
    let url = "/checkaccountemail/" + email;

	emailHttp.onreadystatechange = EmailStateChanged;
	emailHttp.open("GET", url, true);
	emailHttp.send(null);
}

const AccountStateChanged = () =>
{
	if (accountHttp.readyState == 4)
	{
        let response = JSON.parse(accountHttp.response)

        let account = document.getElementById("account_name")
        let accountCheck = document.getElementById("acc_name_check")

        if(response.code == 400){
            if(account.classList.contains('is-invalid') === false){
                account.classList.add('is-invalid')
                accountCheck.classList.add('invalid-feedback')
            }

            if(account.classList.contains('is-valid')){
                account.classList.remove('is-valid')
                accountCheck.classList.remove('valid-feedback')
            }
        }

        if(response.code == 200){
            if(account.classList.contains('is-valid') === false){
                account.classList.add('is-valid')
                accountCheck.classList.add('valid-feedback')
            }

            if(account.classList.contains('is-invalid')){
                account.classList.remove('is-invalid')
                accountCheck.classList.remove('invalid-feedback')
            }
        }

        accountCheck.innerHTML = response.message;
	}
}

const EmailStateChanged = () =>
{
	if (emailHttp.readyState == 4)
	{
        let response = JSON.parse(emailHttp.response)
        let email = document.getElementById("account_email")
        let emailCheck = document.getElementById("account_email_check")

        if(response.code == 400){
            if(email.classList.contains('is-invalid') === false){
                email.classList.add('is-invalid')
                emailCheck.classList.add('invalid-feedback')
            }

            if(email.classList.contains('is-valid')){
                email.classList.remove('is-valid')
                emailCheck.classList.remove('valid-feedback')
            }
        }

        if(response.code == 200){
            if(email.classList.contains('is-valid') === false){
                email.classList.add('is-valid')
                emailCheck.classList.add('valid-feedback')
            }

            if(email.classList.contains('is-invalid')){
                email.classList.remove('is-invalid')
                emailCheck.classList.remove('invalid-feedback')
            }
        }

        emailCheck.innerHTML = response.message;
    }
}

grecaptcha.ready(function () {
    grecaptcha.execute('{{ config('captcha.sitekey') }}', { action: 'createaccount' }).then(function (token) {
        let recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
    });
});

$('button[type=submit]').on('click', function(){
    $('form[method=POST]').submit()
})
</script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Cadastrar - Se</span>
    </div>
    <div class="bg-content">
        <div class="container">
            @if($errors->has('g-recaptcha-response'))
                @foreach ($errors->get('g-recaptcha-response') as $error)
                    <x-notification message="{{ $error }}" class="mt-3"/>
                @endforeach
            @endif
            @if($errors->has('success'))
                @foreach ($errors->get('success') as $error)
                    <x-notification message="{{ $error }}" class="mt-3"/>
                @endforeach
            @endif

            <div class="bg-title">Crie uma conta no {{ htmlspecialchars(config('otserver.site.serverName')) }}</div>
            <div class="main-content">
                <p>Para jogar no {{ htmlspecialchars(config('otserver.site.serverName')) }} você precisa de uma conta. Tudo que você precisa fazer para criar sua nova conta é inserir seu endereço de e-mail, senha da nova conta, código de verificação da foto e concordar com os termos apresentados abaixo. Caso tenha feito isso, seu nome de conta, senha e endereço de e-mail serão mostrados na página a seguir e sua conta e senha serão enviadas para seu endereço de e-mail junto com mais instruções.</p>
                <div class="row align-items-center">
                    <div class="col-6 px-5">
                        <form action="{{ route('createaccount.index') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control @if($errors->has('account_name')) is-invalid @endif" id="account_name" placeholder="Account"
                                name="account_name" onblur="checkAccount()" autocomplete="new-name">
                                <label for="account_name"><i class="fa fa-user"></i> Account</label>
                                <div id="acc_name_check" class="help-block @if($errors->has('account_name')) invalid-feedback @endif">
                                    @if($errors->has('account_name'))
                                        @foreach ($errors->get('account_name') as $error)
                                        {{ $error }}
                                        @endforeach
                                    @else
                                        (Por favor, entre com seu novo número da conta)
                                    @endif
                                </div>
                            </div>
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control @if($errors->has('account_email')) is-invalid @endif" id="account_email" placeholder="E-mail"
                                name="account_email" onblur="checkEmail()" autocomplete="new-email">
                                <label for="account_email"><i class="fa fa-envelope"></i> E-mail</label>
                                <div id="account_email_check" class="help-block @if($errors->has('account_email')) invalid-feedback @endif">
                                    @if($errors->has('account_email'))
                                        @foreach ($errors->get('account_email') as $error)
                                        {{ $error }}
                                        @endforeach
                                    @else
                                        (O seu endereso de e-mail é necessário)
                                    @endif
                                </div>
                            </div>
                            <div class="form-floating mb-1">
                                <input type="password" class="form-control @if($errors->has('account_password')) is-invalid @endif" id="account_password" placeholder="Senha"
                                name="account_password" autocomplete="new-password">
                                <label for="account_password"><i class="fa fa-lock"></i> Senha</label>
                                <div class="help-block @if($errors->has('account_password')) invalid-feedback @endif">
                                    @if($errors->has('account_password'))
                                        @foreach ($errors->get('account_password') as $error)
                                        {{ $error }}
                                        @endforeach
                                    @else
                                        (Aqui escreva a sua senha para sua conta {{ htmlspecialchars(config('otserver.site.serverName')) }})
                                    @endif
                                </div>
                            </div>
                            <div class="form-floating mb-1">
                                <input type="password" class="form-control @if($errors->has('account_password2')) is-invalid @endif" id="account_password2" placeholder="Repetir Senha"
                                name="account_password2" autocomplete="new-password2">
                                <label for="account_password2"><i class="fa fa-lock"></i> Repetir Senha</label>
                                <div class="help-block @if($errors->has('account_password2')) invalid-feedback @endif">
                                    @if($errors->has('account_password2'))
                                        @foreach ($errors->get('account_password2') as $error)
                                        {{ $error }}
                                        @endforeach
                                    @else
                                        (Repita sua senha)
                                    @endif
                                </div>
                            </div>
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control @if($errors->has('account_rlname')) is-invalid @endif" id="account_rlname" placeholder="Nome Completo"
                                name="account_rlname" autocomplete="new-rlname">
                                <label for="account_rlname"><i class="fas fa-smile"></i> Nome Completo</label>
                                <div class="help-block @if($errors->has('account_rlname')) invalid-feedback @endif">
                                    @if($errors->has('account_rlname'))
                                        @foreach ($errors->get('account_rlname') as $error)
                                        {{ $error }}
                                        @endforeach
                                    @else
                                        (Aqui escreva o nome completo)
                                    @endif
                                </div>
                            </div>
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control @if($errors->has('account_location')) is-invalid @endif" id="account_location" placeholder="Localização"
                                name="account_location" readonly="readonly" autocomplete="new-location">
                                <label for="account_location"><i class="fas fa-map-marked-alt"></i> Localização</label>
                                <div class="help-block @if($errors->has('account_location')) invalid-feedback @endif">
                                    @if($errors->has('account_location'))
                                    @foreach ($errors->get('account_location') as $error)
                                    {{ $error }}
                                    @endforeach
                                    @else
                                        (Aqui escreva a sua localização)
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
                            <div class="form-floating">
                                <textarea class="form-control @if($errors->has('account_rules')) is-invalid @endif" id="rules" style="height: 300px" readonly="readonly"></textarea>
                                <label for="rules">{{ htmlspecialchars(config('otserver.site.serverName')) }} Rules</label>
                            </div>
                            <div class="col">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @if($errors->has('account_rules')) is-invalid @endif" type="checkbox" id="rules-checkbox" name="account_rules" value="true">
                                    <label class="form-check-label" for="rules-checkbox">Concordo com o <a href="index.php?p=contrato">Contrato de Serviço</a>, as <a href="index.php?p=regras">regras do jogo</a> e com a Política de Privacidade do {{ htmlspecialchars(config('otserver.site.serverName')) }}.</label>
                                    <div class="help-block @if($errors->has('account_rules')) invalid-feedback @endif">
                                        @if($errors->has('account_rules'))
                                        @foreach ($errors->get('account_rules') as $error)
                                        {{ $error }}
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col mt-2">
                                <p class="m-0">Se você concorda plenamente com estes termos, clique em <b>"Confimar"</b> para criar sua conta no {{ htmlspecialchars(config('otserver.site.serverName')) }}.</p>
                                <p class="m-0">Se você não concordar com esses termos ou não quer criar um conta no {{ htmlspecialchars(config('otserver.site.serverName')) }} por favor clique no botão <b>"Cancelar"</b>.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-2">
                    <button type="submit" class="sbutton-blue">Confirmar</button>
                    <a type="button" class="sbutton-red" href="lostaccount">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
