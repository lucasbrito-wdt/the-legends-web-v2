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
            <div class="bg-title">Passando a liderança</div>
            @if($errorsView)
            <div class="main-content">
                @foreach($errors->all() as $message)
                <p class="text-center m-0">{!! $message !!}</p>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12 mt-1">
                    <a href="{{ route('guilds.manager', ['guildId' => $guild->getId() ]) }}" class="sbutton-red mx-auto d-block">Voltar</a>
                </div>
            </div>
            @else
            <div class="main-content mb-2">
                <form id="formGuild" action="{{ route('guilds.passleadership', ['guildId' => $guild->getId()]) }}" method="POST" class="row d-flex flex-column align-items-center">
                    @csrf
                    <div class="col-5">
                        <div class="form-floating mb-3">
                            <input type="text" name="player" class="form-control @error('player') is-invalid @enderror" id="player" placeholder="Passar a liderança para">
                            <label for="player">Passar a liderança para:</label>
                            @error('player')
                                <div class="help-block @error('player') invalid-feedback @enderror">{!! $message !!}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-center">
                <button id="submitGuild" type="submit" class="sbutton-blue">Salvar</button>
                <a href="{{ route('guilds.manager', ['guildId' => $guild->getId() ]) }}" class="sbutton-red">Cancelar</a>
            </div>
            @endif
        </div>
    </div>
  </div>
@endsection
