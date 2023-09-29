@extends('layout.index')
@section('title', 'Tabela de Experiência')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Experience Table</span>
    </div>
    <div class="bg-content" style="padding-top: 26px;padding-bottom: 26px;">
        <div class="container">
            <div class="bg-title">Tabela de Experiência do {{ config('otserver.server.serverName') }}</div>
            <div class="main-content">
                @foreach (\Website::getStages() as $worldId => $world)
                <table class="table table-striped table-bordered mb-0">
                    <thead>
                        <tr class="table-dark">
                            <th colspan="2">Mundo: {{ config('otserver.site.worlds')[(int)$worldId] }}</th>
                        </tr>
                        <tr>
                            <th>Nível</th>
                            <th>Experiência</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($world->getStages() as $stage)
                            <tr>
                                <td>{{ $stage->getMinlevel() }}</td>
                                <td>{{ \Functions::getExpForLevel($stage->getMinlevel()) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
