@extends('layout.index')
@section('title', 'Ultimas Mortes')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Ultimas Mortes</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Ultimas Mortes:</div>
            <div class="main-content p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered m-0">
                        <thead class="table-dark">
                            <th>*</th>
                            <th>Data</th>
                            <th>Morte</th>
                            <th>Mundo</th>
                        </thead>
                        <tbody>
                            @foreach ($deaths as $death)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($death->getDate())->isoFormat('A, d [de] MMMM [de] Y')}}</td>
                                <td>
                                    <a href="{{ route('characters.index', ['name' => urlencode($death->data['name'])]) }}"><b>{{ $death->data['name'] }}</b></a>
                                    @foreach ($death->loadKillers() as $killer)
                                        @if ($killer['player_name'] != "")
                                            @if($loop->iteration == 1)
                                                {!! _("morto no level <b>{$death->getLevel()}</b> para") !!}
                                            @elseif($loop->iteration == $loop->count)
                                                {{ _(' e ') }}
                                            @else
                                                {{ _(', ') }}
                                            @endif
                                            @if($killer['monster_name'] != "")
                                                {{ _("{$killer['monster_name']} convocado pelo ") }}
                                            @endif
                                            @if($killer['player_exists'] == 0)
                                                <a href="{{ route('characters.index', ['name' => urlencode($killer['player_name'])]) }}"><b>{{ $killer['player_name'] }}</b></a>
                                            @endif
                                        @else
                                            @if($loop->iteration == 1)
                                                {!! _("morto no level <b>{$death->getLevel()}</b>") !!}
                                            @elseif($loop->iteration == $loop->count)
                                                {{ _(' e ') }}
                                            @else
                                                {{ _(',') }}
                                            @endif
                                            {!! _(" para <b>". ucwords($killer['monster_name']."</b>")) !!}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ config('otserver.site.worlds')[$death->data['world_id']] }}</td>
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
