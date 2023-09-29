@extends('layout.index')
@section('title', 'WebShop')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>WebShop</span>
    </div>
    <div class="bg-content" style="padding-top: 26px;padding-bottom: 26px;">
        <div class="container">
            <div class="bg-title m-0">WebShop no {{ config('otserver.server.serverName') }}</div>
            <div class="main-content">
                <h1 class="d-block text-center py-3">Sucesso !</h1>
                <i class="d-block text-center fa fa-check-circle text-success fw-bold py-3 mb-3" style="font-size: 150px"></i>
                <div class="d-block text-center fw-bold fs-2">Pagamento efeutado com sucesso!</div>
            </div>
            <a href="{{ route('webshop.index') }}" class="sbutton-blue d-block mx-auto mt-2">Voltar</a>
        </div>
    </div>
</div>
@endsection
