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
            @else
            <div class="main-content">
                <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <th scope="col">*</th>
                        <th scope="col">Item</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Points</th>
                        <th scope="col"><i class="fas fa-cogs"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($shopOffer as $offer)
                            <tr>
                                <td scope="row" class="bg-secondary bg-gradient d-flex align-items-center justify-content-center" style="height: 83px;width: 83px;">
                                    @if($offer->getOfferType() == "item" || $offer->getOfferType() == "itemlogout")
                                        <img src="{{ asset('images/shop/'.$offer->getItemId1().'.png') }}" alt="{{ htmlspecialchars($offer->getOfferName()) }}" height="40">
                                    @elseif($offer->getOfferType() == "container")
                                    <div class="d-flex align-content-center justify-content-center position-relative">
                                        <img src="{{ asset('images/shop/'.$offer->getItemId2().'.png') }}" alt="{{ htmlspecialchars($offer->getOfferName()) }}" height="40">
                                        <span class="position-absolute fw-bold text-white" style="top: 11px;right: 7px;font-size: 8px;z-index: 1;">{{ $offer->getCount1() }}</span>
                                        <img src="{{ asset('images/shop/'.$offer->getItemId1().'.png') }}" alt="{{ htmlspecialchars($offer->getOfferName()) }}" height="27" class="position-absolute" style="top: 12px;left: 6px;">
                                    </div>
                                    @elseif($offer->getOfferType() == "pacc")
                                    <div class="d-flex align-content-center justify-content-center position-relative">
                                        <img src="{{ asset('images/shop/22.png') }}" alt="{{ htmlspecialchars($offer->getOfferName()) }}" height="40">
                                        <span class="position-absolute fw-bold text-white" style="top: 1px;right: -2px;font-size: 8px;z-index: 1;">{{ $offer->getCount1() }}</span>
                                    </div>
                                    @elseif($offer->getOfferType() == "changename")
                                        Trocar Nome
                                    @elseif($offer->getOfferType() == "unban")
                                        UnBan
                                    @elseif($offer->getOfferType() == "frags")
                                        UnFrags
                                    @elseif($offer->getOfferType() == "redskull")
                                        UnRedSkull
                                    @endif
                                </td>
                                <td class="col-2" scope="row">{{ htmlspecialchars($offer->getOfferName()) }}</td>
                                <td class="text-start" scope="row">{{ htmlspecialchars($offer->getOfferDescription()) }}</td>
                                <td scope="row">{{ htmlspecialchars($offer->getPoints()) }}</td>
                                <td scope="row" class="col-1">
                                @if(!$logged)
                                    <a href="{{ route('accountmanagement.login') }}">Faça login</a>
                                @else
                                    <a href="{{ route('shop.selectplayer', ['buyId' => $offer->getId()]) }}">Comprar</a>
                                @endif
                                </td>
                            </tr>
                       @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
