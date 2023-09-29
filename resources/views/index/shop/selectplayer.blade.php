@extends('layout.index')
@section('title', 'Loja')
@push('scripts')
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Loja</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Bem Vindo a loja Online!</div>
            @if($errorsView)
            <div class="main-content">
                @foreach($errors->all() as $message)
                <p class="text-center m-0">{!! $message !!}</p>
                @endforeach
            </div>
            @if(!$logged)
            <div class="d-flex justify-content-center mt-1">
                <a href="{{ route('accountmanagement.login') }}" class="sbutton-blue">Login</a>
            </div>
            @endif
            @else
            <div class="main-content">
                <div class="row d-flex justify-content-center">
                    <div class="col-6">
                        @if($buyOffer->getOfferType() != "changename")
                        <div class="card mb-1">
                            <div class="card-header text-white bg-dark">Oferta selecionada</div>
                            <div class="card-body">
                              <div class="card-text">
                                <div class="row">
                                    <div class="col-2">Nome:</div>
                                    <div class="col">{{ htmlspecialchars($buyOffer->getOfferName()) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-2">Descrição:</div>
                                    <div class="col">{{ htmlspecialchars($buyOffer->getOfferDescription()) }}</div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <form action="{{ route('shop.confirmtransaction') }}" method="POST" class="card mb-1">
                            @csrf
                            <input type="hidden" name="buy_id" value="{{ $buyOffer->getID() }}">
                            <div class="card-header text-white bg-dark">Compra para um personagem da sua conta</div>
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-2 d-flex align-items-center">Nome:</div>
                                        <div class="col">
                                            <div class="form-floating">
                                                <select class="form-select" id="buy_name" name="buy_name" aria-label="Selecione um personagem">
                                                    <option selected>Selecione um personagem</option>
                                                    @foreach ($players_from_logged_acc as $player)
                                                        <option value="{{ urlencode(htmlspecialchars($player->getName())) }}">{{ htmlspecialchars($player->getName()) }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="buy_name">Selecione um personagem</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="sbutton-blue d-block mx-auto">Comprar</button>
                            </div>
                        </form>

                        <form action="{{ route('shop.confirmtransaction') }}" method="POST" class="card">
                            @csrf
                            <input type="hidden" name="buy_id" value="{{ $buyOffer->getID() }}">
                            <div class="card-header text-white bg-dark">Doação</div>
                            <div class="card-body">
                              <p class="card-text">
                                <div class="row mb-1">
                                    <div class="col-2 d-flex align-items-center">De:</div>
                                    <div class="col">
                                        <div class="form-floating">
                                            <select class="form-select" id="buy_name" name="buy_name" aria-label="Selecione um personagem">
                                                <option selected>Selecione um personagem</option>
                                                @foreach ($players_from_logged_acc as $player)
                                                    <option value="{{ urlencode(htmlspecialchars($player->getName())) }}">{{ htmlspecialchars($player->getName()) }}</option>
                                                @endforeach
                                            </select>
                                            <label for="buy_name">Selecione um personagem</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2 d-flex align-items-center">Para:</div>
                                    <div class="col">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="buy_from" name="buy_from" placeholder="Digite um nome do jogador">
                                            <label for="buy_from">Digite um nome do jogador</label>
                                        </div>
                                    </div>
                                </div>
                              </p>
                            </div>
                            <div class="card-footer">
                                <button class="sbutton-blue d-block mx-auto">Doar</button>
                            </div>
                        </form>
                        @else
                        <form action="{{ route('shop.confirmtransaction') }}" method="POST" class="card mb-1">
                            @csrf
                            <input type="hidden" name="buy_id" value="{{ $buyOffer->getID() }}">
                            <div class="card-header text-white bg-dark">Trocar Nome</div>
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row mb-1">
                                        <div class="col-2 d-flex align-items-center">Nome:</div>
                                        <div class="col">
                                            <div class="form-floating">
                                                <select class="form-select" id="buy_name" name="buy_name" aria-label="Selecione um personagem">
                                                    <option selected>Selecione um personagem</option>
                                                    @foreach ($players_from_logged_acc as $player)
                                                        <option value="{{ urlencode(htmlspecialchars($player->getName())) }}">{{ htmlspecialchars($player->getName()) }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="buy_name">Selecione um personagem</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2 d-flex align-items-center">Novo nome:</div>
                                        <div class="col">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="buy_from" name="buy_from" placeholder="Digite um novo nome">
                                                <label for="buy_from">Digite um novo nome</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="sbutton-blue d-block mx-auto">Mude o nome</button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
