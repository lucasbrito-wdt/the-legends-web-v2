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
            <div class="bg-title">Alterar Descrição</div>
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
                <form id="formGuild" action="{{ route('guilds.changedescription', ['guildId' => $guild->getId()]) }}" method="POST" class="row d-flex flex-column align-items-center">
                    @csrf
                    <div class="col-5">
                        <p>Aqui você pode alterar a descrição de sua guild.</p>
                        <div class="form-floating mb-3">
                            <textarea type="text" name="description" class="form-control" id="description" cols="60" rows="{{ config('otserver.site.guild_description_lines_limit') - 1 }}">{{ $guild->getDescription() }}</textarea>
                            <label for="description">Descrição:</label>
                            <div class="help-block">
                                (máx. {{ config('otserver.site.guild_description_lines_limit') }} linhas, máx. {{ config('otserver.site.guild_description_chars_limit') }} caracteres)
                            </div>
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
