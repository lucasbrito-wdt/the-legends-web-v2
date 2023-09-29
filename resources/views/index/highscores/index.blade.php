@extends('layout.index')
@section('title', 'Ranking')
@push('scripts')
<script>
$(document).ready( function () {
    $('#table').DataTable({
        searching: false,
        ordering: true,
        pageLength: 50,
        select: false,
        lengthChange: false,
        columnDefs: [{
            targets: 1,
            searching: false,
            ordering: false
        }],
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "",
            "oPaginate": {
            "sNext": "Próximo",
            "sPrevious": "Anterior",
            "sFirst": "Primeiro",
            "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });
});
</script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Ranking</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Ranking de no {{ htmlspecialchars(config('otserver.site.serverName')) }}</div>
            <div class="main-content">
                <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th colspan="9" scope="col">Escolha uma skill</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'experience']) }}">
                                    <img src="{{ asset('images/skills/level.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'magic']) }}">
                                    <img src="{{ asset('images/skills/magic_level.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'shield']) }}">
                                    <img src="{{ asset('images/skills/shield.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'distance']) }}">
                                    <img src="{{ asset('images/skills/distance.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'sword']) }}">
                                    <img src="{{ asset('images/skills/sword.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'club']) }}">
                                    <img src="{{ asset('images/skills/club.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'axe']) }}">
                                    <img src="{{ asset('images/skills/axe.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'fist']) }}">
                                    <img src="{{ asset('images/skills/fist.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                            <td class="col">
                                <a href="{{ route('ranking.index', ['list' => 'fishing']) }}">
                                    <img src="{{ asset('images/skills/fishing.gif') }}" alt="" class="img-fluid">
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td class="col">LEVEL</td>
                            <td class="col">ML</td>
                            <td class="col">SHIELD</td>
                            <td class="col">DIST</td>
                            <td class="col">SWORD</td>
                            <td class="col">CLUB</td>
                            <td class="col">AXE</td>
                            <td class="col">FIST</td>
                            <td class="col">FISH</td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <div class="table-responsive">
                <table id="table" class="table table-striped table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="row">Rank</th>
                            <th scope="row">Outfit</th>
                            <th scope="row">Nome</th>
                            <th scope="row">Vocação</th>
                            <th scope="row">Resets</th>
                            @if($list == "experience")
                            <th scope="row">Level</th>
                            <th scope="row">Experience</th>
                            @elseif($list == "magic")
                            <th scope="row">ML</th>
                            @else
                            <th scope="row">Skill</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skills as $skill)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img src="{{ asset(config('otserver.site.outfit_images_url'))."?id={$skill->getLookType()}&addons={$skill->getLookAddons()}&head={$skill->getLookHead()}&body={$skill->getLookBody()}&legs={$skill->getLookLegs()}&feet={$skill->getLookFeet()}" }}" class="img-fluid" /></td>
                            <td>
                                <a href="{{ route('characters.index', ['name' => urlencode($skill->getName())]) }}" class="text-dark fw-bold text-decoration-none">
                                    @if($skill->getOnline() > 0)
                                        <span class="text-success fw-bold">{{ htmlspecialchars($skill->getName()) }}</span>
                                    @else
                                        <span class="text-danger fw-bold">{{ htmlspecialchars($skill->getName()) }}</span>
                                    @endif
                                    <img src="{{ asset(config('otserver.site.flag_images_url'))."/{$skill->getFlag()}".config('otserver.site.flag_images_extension') }}" title="Country: {{ $skill->getFlag() }}" alt="{{ $skill->getFlag() }}" />
                                </a>
                            </td>
                            <td>{{ htmlspecialchars(Website::getVocationName($skill->getVocation())) }}</td>
                            <td>{{ $skill->getResets() }}</td>
                            @if($list == "experience")
                            <td>{{ $skill->getLevel() }}</td>
                            <td>{{ $skill->getExperience() }}</td>
                            @elseif($list == "magic")
                            <td>{{ $skill->getMagLevel() }}</td>
                            @else
                            <td>{{ $skill->getScore() }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
