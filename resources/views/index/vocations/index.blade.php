@extends('layout.index')
@section('title', 'Vocations')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Vocations</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-9 col-xl-10 p-0 pe-md-5">
                    <div class="bg-title">Sorcerer:</div>
                    <div class="main-content">
                        <div class="row">
                            <div class="col-12 col-lg-3">
                                <img src="{{ asset('images/icons/sorcerer.jpg') }}" class="img-thumbnail img-fluid mx-auto d-block">
                            </div>
                            <div class="col-12 col-lg-9 position-relative">
                                <h4><b>{{ implode(' > ', $linSoc) }}: LINHAGEM SORCERER</b></h4>
                                <p>Como druids, sorcerers focam o uso de magia. Similar aos seus irmãos mais pacífico, suas habilidades de armas são muito limitadas, no entanto, os sorcerers têm um grande potencial.</p>
                                <div class="position-absolute bottom-0 start-0">
                                    <a href="{{ route('vocations.show', ['vocation' => 'sorcerer']) }}" style="padding-left: 15px">Veja mais</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-title">Druid:</div>
                    <div class="main-content">
                        <div class="row">
                            <div class="col-12 col-lg-3">
                                <img src="{{ asset('images/icons/druid.jpg') }}" class="img-thumbnail img-fluid mx-auto d-block">
                            </div>
                            <div class="col-12 col-lg-9 position-relative">
                                <h4><b>{{ implode(' > ', $linEd) }}: LINHAGEM DRUID</b></h4>
                                <p>Druids são usuários de pura magia. Como magos, eles são fracos de construção, as habilidades de sua arma são bastante limitados. Druids têm um pequeno número de magias ofensivas à seu uso.</p>
                                <div class="position-absolute bottom-0 start-0">
                                    <a href="{{ route('vocations.show', ['vocation' => 'druid']) }}" style="padding-left: 15px">Veja mais</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-title">Paladin:</div>
                    <div class="main-content">
                        <div class="row">
                            <div class="col-12 col-lg-3">
                                <img src="{{ asset('images/icons/paladin.jpg') }}" class="img-thumbnail img-fluid mx-auto d-block">
                            </div>
                            <div class="col-12 col-lg-9 position-relative">
                                <h4><b>{{ implode(' > ', $linPala) }}: LINHAGEM PALADIN</b></h4>
                                <p>Archers também são lutadores bastante talentosos, embora eles não sejam tão resistentes como knights. Porém sua capacidade de luta é à distância. Archers podem aprender a lidar com qualquer arma de distância com precisão mortal.</p>
                                <div class="position-absolute bottom-0 start-0">
                                    <a href="{{ route('vocations.show', ['vocation' => 'paladin']) }}" style="padding-left: 15px">Veja mais</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-title">Knight:</div>
                    <div class="main-content">
                        <div class="row">
                            <div class="col-12 col-lg-3">
                                <img src="{{ asset('images/icons/knight.jpg') }}" class="img-thumbnail img-fluid mx-auto d-block">
                            </div>
                            <div class="col-12 col-lg-9 position-relative">
                                <h4><b>{{ implode(' > ', $linKina) }}: LINHAGEM KNIGHT</b></h4>
                                <p>Knights são os guerreiros mais resistentes no {{ config('otserver.server.serverName') }}. Eles são fortes, resistentes e que pode manejar qualquer arma branca com uma eficiência assustadora. Em combate, eles são encontrados sempre na linha da frente.</p>
                                <div class="position-absolute bottom-0 start-0">
                                    <a href="{{ route('vocations.show', ['vocation' => 'knight']) }}" style="padding-left: 15px">Veja mais</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-2 d-none d-md-block p-0">
                    {!! view('layout.sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
