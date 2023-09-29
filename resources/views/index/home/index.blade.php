@extends('layout.index')
@section('title', 'Home')
@section('content')
<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators">
      <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
      <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
      <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="bd-placeholder-img d-block w-100" src="{{ asset('images/news/slide-3.jpg') }}"/>
        <div class="container">
          @php
            $serverName = explode(" ", config('otserver.site.serverName'));
          @endphp
          <div class="carousel-caption text-start">
            <h1>{{ $serverName[0] }}</h1>
            <h6>{{ $serverName[1] }}</h6>
            <p>CADASTRE-SE AGORA, E VENHA FAZER A MAIOR AVENTURA DA SUA VIDA AGORA MESMO.</p>
            <p><a class="btn btn-lg" href="{{ route('createaccount.index') }}" role="button" style="width: 150px">Criar Conta</a></p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <img class="bd-placeholder-img d-block w-100" src="{{ asset('images/news/slide-2.jpg') }}"/>
        <div class="container">
          <div class="carousel-caption">
            <h1>NOVAS</h1>
            <h6>ATUALIZAÇÕES</h6>
            <p>MAGIAS, ITENS, CIDADES, QUESTS, MONSTROS, VOCAÇÕES, HUNTERS, EVENTOS E MUITO MAIS.</p>
            <p><a class="btn btn-lg" href="#" role="button">VER MAIS</a></p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <img class="bd-placeholder-img d-block w-100" src="{{ asset('images/news/slide-1.jpg') }}"/>

        <div class="container">
          <div class="carousel-caption text-end">
            <h1>FAÇA</h1>
            <h6>SUA DOAÇÃO</h6>
            <p>Realize uma doação em nosso site e receba The LegenDs Points e seja comtemplado com presentes.</p>
            <p><a class="btn btn-lg" href="#" role="button">Doar Agora</a></p>
          </div>
        </div>
      </div>
    </div>
    <a class="carousel-control-prev" href="#myCarousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </a>
    <div id="gradient"></div>
</div>

  <div class="container bg-border">
    <div class="bg-headline">Jogadores em Destaque</div>
    <div class="bg-content">
        <div class="row">
            @foreach($tops as $player)
                <div id="destaque" class="{{ $loop->first ? 'col-12' : 'col-6' }} col-md-4 text-center">
                    <img src="{{ asset(config('otserver.site.outfit_images_url')).'?id='.$player['looktype'].'&addons='.$player['lookaddons'].'&head='.$player['lookhead'].'&body='.$player['lookbody'].'&legs='.$player['looklegs'].'&feet='.$player['lookfeet'] }}" alt="{{$player['name']}}" width="90" class="mb-2" style="margin-left: -45px;"/>
                    <div class="destaque-footer">
                    <h3><a class="text-decoration-none text-dark" href="{{ route('characters.index', ['name' => urlencode($player['name'])]) }}">{{ $loop->index + 1 }}. {{$player['name']}}</a></h3>
                    <p class="m-0">Level: {{$player['level']}}</p>
                    <p class="m-0">Resets: {{$player['resets']}}</p>
                    <p class="m-0">Vocação: {{ Website::getVocationName($player['vocation'])}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
  </div>

  <div class="container bg-border">
    <div class="bg-headline">Novidades</div>
    <div class="bg-content">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-9 col-xl-10 pt-3 p-0 pe-md-5">
                    @foreach($newsletters as $newsletter)
                        <div class="newsheadline padding-title">
                            <div class="newsheadlinebackground">
                                <i class="newsicon">
                                <img src="{{ asset('images/content/newsicon_development_big.gif') }}" class="img-fluid">
                                </i>
                                <div class="newsheadlinedate">{{ $newsletter["created_at"] }} -</div>
                                <div class="newsheadlinetext">{{ $newsletter["author"] }}</div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="newsarticleimg float-end">
                                <img src="{{ asset('images/newsletter/'.$newsletter["image"]) }}" class="img-fluid">
                            </div>
                            <div class="newsarticletext">{!! $newsletter["text"] !!}</div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-4 col-lg-3 col-xl-2 d-none d-md-block p-0">
                    {!! view('layout.sidebar') !!}
                </div>
            </div>
            </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
    const myCarousel = document.querySelector('#myCarousel')
    const carousel = new bootstrap.Carousel(myCarousel, {
        interval: 2000,
        wrap: false
    })
</script>
@endsection
