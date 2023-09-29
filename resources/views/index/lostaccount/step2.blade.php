@extends('layout.index')
@section('title', 'LostAccount')
@push('scripts')
<script>
    $('#submit').click(function(){
        $('#form').submit();
    })

    const validate_required = (field, alertText) => {
        if (field.value == null || field.value == "" || field.value == " ")
        {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');

            $(`.${field.id}-feedback`).addClass('invalid-feedback').text(alertText);
            return false
        }
        else
        {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            $(`.${field.id}-feedback`).addClass('valid-feedback').text('');
            return true
        }
    };

    const validate_email = (field, alertText) => {
        apos = field.value.indexOf("@")
        dotpos = field.value.lastIndexOf(".")
        if (apos < 1 || dotpos - apos < 2)
        {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');

            $(`.${field.id}-feedback`).addClass('invalid-feedback').text(alertText);
            return false
        }
        else
        {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            $(`.${field.id}-feedback`).addClass('valid-feedback').text('');
            return true
        }
    };

    const validate_form = (thisform) => {
        with (thisform)
        {
            if (validate_required(email, "Por favor introduza o seu e-mail!") == false)
            {
                email.focus()
                return false
            }
            if (validate_email(email,"Formato de email inválido!")==false)
            {
                email.focus()
                return false
            }
            if (validate_required(passor,"Por favor, digite a senha!")==false)
            {
                passor.focus()
                return false
            }
            if (validate_required(passor2,"Por favor, repita a senha!")==false)
            {
                passor2.focus()
                return false
            }
            if (passor2.value!=passor.value)
            {
                passor2.classList.add('is-invalid');
                passor2.classList.remove('is-valid');
                $(`.${passor2.id}-feedback`).addClass('invalid-feedback').text('Senha repetida não é igual a senha!');

                return false
            } else {
                passor2.classList.remove('is-invalid');
                passor2.classList.add('is-valid');
                $(`.${passor2.id}-feedback`).addClass('valid-feedback').text('');
                return true;
            }
        }
    }
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
                <div class="row d-felx justify-content-center">
                    <div class="col-6">
                        <p class="d-block text-center">Defina uma nova senha e e-mail para sua conta.</p>
                        <form id="form" action="{{ route('lostaccount.step3') }}" method="post" onsubmit="return validate_form(this)">
                            @csrf
                            <input type="hidden" name="key" value="{{ htmlspecialchars($rec_key) }}">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nick" name="nick" placeholder="Conta do personagem" value="{{ htmlspecialchars($nick) }}" readonly="readonly">
                                <label for="nick">Conta do personagem</label>
                                <div class="nick-feedback"></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="passor" name="passor" placeholder="Por favor insira a nova senha">
                                <label for="passor">Nova senha</label>
                                <div class="passor-feedback"></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="passor2" name="passor2" placeholder="Por favor insira a nova senha novamente">
                                <label for="passor2">Repita a nova senha</label>
                                <div class="passor2-feedback"></div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Por favor insira o novo e-mail">
                                <label for="email">Novo Endereço de e-mail</label>
                                <div class="email-feedback"></div>
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
