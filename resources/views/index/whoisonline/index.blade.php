@extends('layout.index')
@section('title', 'Quem está online?')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Quem está online?</span>
    </div>
    <div class="bg-content" style="padding-top: 26px;padding-bottom: 26px;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-title mt-0">Informação do Mundo</div>
                        <div class="main-content">
                            <div class="row">
                                <div class="col-2 fw-bold">Status:</div>
                                <div class="col-10 fw-bold {{ config('otserver.status.serverStatus_online') == 1 ? 'text-success' : 'text-danger' }}">{{ config('otserver.status.serverStatus_online') == 1 ? _('Online') : _('Offline') }}</div>

                                <div class="col-2 fw-bold">Players Online:</div>
                                <div class="col-10">{{ $players->count() }} Players Online</div>

                                <div class="col-2 fw-bold">Online Record:</div>
                                <div class="col-10">{{ $record['r']._(' players (em '.\Carbon\Carbon::createFromTimestamp($record['t'])->isoFormat('MMM DD Y hh:mm:ss z').')') }}</div>

                                <div class="col-2 fw-bold">Data de criação:</div>
                                <div class="col-10">{{ ucwords(\Carbon\Carbon::now()->isoFormat('D [de] MMMM [de] Y')) }}</div>

                                <div class="col-2 fw-bold">Localização:</div>
                                <div class="col-10">{{ config('otserver.server.location') }}</div>

                                <div class="col-2 fw-bold">Tipo PvP:</div>
                                <div class="col-10">
                                    @php
                                        $w = strtolower(config('otserver.server.worldType'));
                                    @endphp
                                    @if(in_array($w, array('pvp','2','normal','open','openpvp')))
                                    Open PvP
                                    @elseif(in_array($w, array('no-pvp','nopvp','non-pvp','nonpvp','1','safe','optional','optionalpvp')))
                                    Optional PvP
                                    @elseif(in_array($w, array('pvp-enforced','pvpenforced','pvp-enfo','pvpenfo','pvpe','enforced','enfo','3','war','hardcore','hardcorepvp')))
                                    Hardcore PvP
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-title">Players Online</div>
                        <div class="main-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0 text-center">
                                            <thead>
                                                <tr class="table-dark">
                                                    <th class="text-center">*</th>
                                                    <th>Nome <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => 'name', 'orderDirection' => $orderDirection != null && $orderDirection == 'asc' ? 'desc' : 'asc', 'orderAlphabetic' => $orderAlphabetic]) }}">[sort]</a></th>
                                                    <th>Level <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => 'level', 'orderDirection' => $orderDirection != null && $orderDirection == 'asc' ? 'desc' : 'asc', 'orderAlphabetic' => $orderAlphabetic]) }}">[sort]</a></th>
                                                    <th>Vocação <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => 'vocation', 'orderDirection' => $orderDirection != null && $orderDirection == 'asc' ? 'desc' : 'asc', 'orderAlphabetic' => $orderAlphabetic]) }}">[sort]</a></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="4" class="text-center">
                                                        <span>Order Alphabetic: </span>
                                                        [
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'A']) }}" class="text-dark text-decoration-none">A</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'B']) }}" class="text-dark text-decoration-none">B</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'C']) }}" class="text-dark text-decoration-none">C</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'D']) }}" class="text-dark text-decoration-none">D</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'E']) }}" class="text-dark text-decoration-none">E</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'F']) }}" class="text-dark text-decoration-none">F</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'G']) }}" class="text-dark text-decoration-none">G</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'H']) }}" class="text-dark text-decoration-none">H</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'I']) }}" class="text-dark text-decoration-none">I</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'J']) }}" class="text-dark text-decoration-none">J</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'K']) }}" class="text-dark text-decoration-none">K</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'L']) }}" class="text-dark text-decoration-none">L</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'M']) }}" class="text-dark text-decoration-none">M</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'N']) }}" class="text-dark text-decoration-none">N</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'O']) }}" class="text-dark text-decoration-none">O</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'P']) }}" class="text-dark text-decoration-none">P</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'Q']) }}" class="text-dark text-decoration-none">Q</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'R']) }}" class="text-dark text-decoration-none">R</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'S']) }}" class="text-dark text-decoration-none">S</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'T']) }}" class="text-dark text-decoration-none">T</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'U']) }}" class="text-dark text-decoration-none">U</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'V']) }}" class="text-dark text-decoration-none">V</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'W']) }}" class="text-dark text-decoration-none">W</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'X']) }}" class="text-dark text-decoration-none">X</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'Y']) }}" class="text-dark text-decoration-none">Y</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => 'Z']) }}" class="text-dark text-decoration-none">Z</a>
                                                            <a href="{{ route('whoisonline.index', ['world' => $world, 'order' => $order, 'orderDirection' => $orderDirection, 'orderAlphabetic' => '']) }}" class="text-dark text-decoration-none">-</a>
                                                        ]
                                                    </th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                @foreach ($players as $player)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <a href="{{ route('characters.index', ['name' => urlencode($player->getName())]) }}" class="text-dark fw-bold text-decoration-none">
                                                                @if($player->getOnline() > 0)
                                                                    <span class="text-success fw-bold">{{ htmlspecialchars($player->getName()) }}</span>
                                                                @else
                                                                    <span class="text-danger fw-bold">{{ htmlspecialchars($player->getName()) }}</span>
                                                                @endif
                                                                <img src="{{ asset(config('otserver.site.flag_images_url'))."/{$player->getAccount()->getFlag()}".config('otserver.site.flag_images_extension') }}" title="Country: {{ $player->getAccount()->getFlag() }}" alt="{{ $player->getAccount()->getFlag() }}" />
                                                            </a>
                                                        </td>
                                                        <td>{{ $player->getLevel() }}</td>
                                                        <td>{{ \Website::getVocationName($player->getVocation()) }}</td>
                                                    </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
