@extends('layout.index')
@section('title', 'Vocations')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Vocation</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Informações:</div>
            <div class="main-content">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <img src="{{ asset("images/icons/{$vocation}.jpg") }}" class="img-thumbnail img-fluid mx-auto d-block">
                    </div>
                    <div class="col-12 col-lg-9 position-relative">
                        <h4><b>{{ implode(' > ', $lin) }}: <span class="text-uppercase">LINHAGEM {{ $vocation }}</span></b></h4>
                        <p>{{ $info }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-title">Atributo e Informações:</div>
            <div class="main-content p-0 pt-1">
                <div class="table-responsive">
                    <table class="table table-striped align-middle text-center m-0">
                        <thead class="table-dark">
                            <th cope="col" class="text-start">Informações e dados técnicos:</th>
                            @foreach($lin as $name)
                            <th cope="col">{{ $name }}</th>
                            @endforeach
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start">Pontos de HP ganhos por level:</td>
                                @foreach ($linVocs as $item)
                                <td>{{ $item->getGainHp() }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-start">Pontos de MP ganhos por level:</td>
                                @foreach ($linVocs as $item)
                                <td>{{ $item->getGainMana() }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-start">Pontos de capacidade ganhos por level:</td>
                                @foreach ($linVocs as $item)
                                <td>{{ $item->getGainCap() }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-start">Velocidade de Ataque:</td>
                                @foreach ($linVocs as $item)
                                <td>{{ $item->getAttackSpeed() / 1000 }} sec</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
