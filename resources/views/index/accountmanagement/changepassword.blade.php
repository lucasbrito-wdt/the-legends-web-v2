@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
                <div class="bg-title">Alterar Senha</div>
                <div class="main-content">
                    @foreach($errors->all() as $message)
                        <x-notification message="{{ $message }}" isAutoClose="false" class="mt-3"/>
                    @endforeach
                    <p class="mb-2 text-center">Por favor insira sua senha atual e a nova senha. Para sua segurança, por favor digite a nova senha duas vezes.</p>
                    <form id="changepasswordForm" action="changepassword" method="post" class="col-5 mx-auto">
                        @csrf
                        <div class="form-floating mb-1">
                            <input type="password" class="form-control" id="oldpassword" placeholder="Senha Atual:"
                            name="oldpassword" maxlength="29">
                            <label for="oldpassword">Senha Atual:</label>
                        </div>
                        <div class="form-floating mb-1">
                            <input type="password" class="form-control" id="newpassword" placeholder="Nova senha:"
                            name="newpassword" maxlength="29">
                            <label for="newpassword">Nova senha:</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="newpassword2" placeholder="Nova senha novamente:"
                            name="newpassword2">
                            <label for="newpassword2" maxlength="29">Nova senha novamente:</label>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button type="submit" class="sbutton-blue" onclick="changepasswordForm()">Alterar Senha</button>
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        const changepasswordForm = () => {
            document.getElementById("changepasswordForm").submit();
        }
    </script>
@endpush
