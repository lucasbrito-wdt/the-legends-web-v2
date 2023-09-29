@extends('layout.index')
@section('title', 'Guilds')
@push('scripts')
<script>
    $('#submitGuild').click(function(){
        $('#formGuild').submit();
    })
</script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Guilds</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Alterar Rank</div>
            @if($errorsView)
            <div class="main-content">
                @foreach($errors->all() as $message)
                <p class="text-center m-0">{!! $message !!}</p>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12 mt-1">
                    <a href="{{ route('guilds.show', ['guildId' => $guild->getId() ]) }}" class="sbutton-red mx-auto d-block">Voltar</a>
                </div>
            </div>
            @else
            <div class="main-content mb-2">
                @error('success')
                    <x-notification message="{!! $message !!}" isAutoClose="false" class="mb-3"/>
                @enderror

                <form id="formGuild" action="{{ route('guilds.changerank', ['guildId' => $guild->getId()]) }}" method="POST" class="row d-flex flex-column align-items-center">
                    @csrf
                    <div class="col-5">
                        <div class="row">
                            <div class="col">
                                <div class="form-floating">
                                    <select name="name" class="form-select" id="name" name="name" aria-label="Selecione o jogador">
                                        @foreach ($players_with_lower_rank as $player_to_list)
                                            <option value="{{ $player_to_list['0'] }}">{{ $player_to_list['1'] }}</option>
                                        @endforeach
                                    </select>
                                    <label for="name">Selecione o jogador:</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <select class="form-select" id="rankid" name="rankid" aria-label="Selecione o rank">
                                        @foreach ($ranks as $rank)
                                            <option value="{{ htmlspecialchars($rank['0']) }}">{{ htmlspecialchars($rank['1']) }}</option>
                                        @endforeach
                                    </select>
                                    <label for="rankid">Selecione o rank:</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-center">
                <button id="submitGuild" type="submit" class="sbutton-blue">Alterar Rank</button>
                <a href="{{ route('guilds.show', ['guildId' => $guild->getId() ]) }}" class="sbutton-red">Cancelar</a>
            </div>
            @endif
        </div>
    </div>
  </div>
@endsection
