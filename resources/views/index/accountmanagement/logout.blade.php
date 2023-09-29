@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Saiu com sucesso</div>
            <div class="main-content">
              Você saiu da sua conta. Para gerenciar sua conta, você precisa fazer <a href="/accountmanagement">login novamente.</a><br />
            </div>
        </div>
    </div>
</div>
@endsection
