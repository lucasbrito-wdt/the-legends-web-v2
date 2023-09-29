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
            <div class="bg-title">Player Dispensado</div>
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

                    <form id="formGuild" action="{{ route('guilds.kickplayer', ['guildId' => $guild->getId(), 'name' => urlencode($player->getName())]) }}" method="POST" class="row d-flex flex-column align-items-center">
                        @csrf
                        <div class="col-5">
                            <p class="m-0">Você quer dispensar o player <b>{{ $player->getName() }}</b> da sua guild?</p>
                        </div>
                    </form>
                </div>
                <div class="d-flex justify-content-center">
                    <button id="submitGuild" type="submit" class="sbutton-blue">Confirmar</button>
                    <a href="{{ route('guilds.show', ['guildId' => $guild->getId() ]) }}" class="sbutton-red">Cancelar</a>
                </div>
            @endif
        </div>
    </div>
  </div>
@endsection
