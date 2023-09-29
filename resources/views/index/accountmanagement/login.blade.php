@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            @foreach($errors->all() as $message)
                <x-notification message="{{ $message }}" class="mt-3"/>
            @endforeach

            <div class="bg-title">Account Login</div>
            <div class="main-content">
                <p>Bem Vindo, jogador. Se você ainda não tive uma conta, Crie a sua <a href="createaccount">conta</a> agora.</p>
                <div class="row align-items-center">
                    <div class="col-6 px-5">
                        <form action="/accountmanagement/login" method="POST">
                            @csrf
                            <div class="form-floating mb-1">
                                <input type="text" class="form-control" id="floatingInput" placeholder="Usuario"
                                name="account_login">
                                <label for="floatingInput">Usuario</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password"
                                name="password_login">
                                <label for="floatingPassword">Senha</label>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <a type="button" class="sbutton-red float-end" href="lostaccount">Conta perdida?</a>
                                    <button type="submit" class="sbutton-blue float-end">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                        <img src="{{ asset('images/bg-content/devovorga_fa_small.png') }}" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
