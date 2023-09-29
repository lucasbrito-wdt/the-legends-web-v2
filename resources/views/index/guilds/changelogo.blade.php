@extends('layout.index')
@section('title', 'Guilds')
@push('scripts')
<script>
    $(document).ready(function(){
        $('[data-bs-toggle="tooltip"]').tooltip()

    })

    $('#submitGuild').click(function(){
        $('#formGuild').submit();
    });

    $("#formFile").change(function(){
        const input = this
        if(input.files && input.files[0]){
            let reader = new FileReader()
            reader.onload = function(e) {
                $('#img-preview').attr('src', e.target.result)
                $('#img-preview').hide()
                $('#img-preview').fadeIn(500)
                $('#file-label').text("Logo Nova")
                $('#close').fadeIn(500);
            }
            reader.readAsDataURL(input.files[0])
        }
    })

    $("#close").on("click", function(){
        $('#img-preview').attr('src', $('#img-preview').data('original-image'))
        $('#img-preview').hide()
        $('#img-preview').fadeIn(500)
        $('#file-label').text("Logo Atual")
        $('#close').hide()
        $('#formFile').val("");
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
            <div class="bg-title">Trocando Logo</div>
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

                <form id="formGuild" action="{{ route('guilds.changelogo', ['guildId' => $guild->getId()]) }}" method="POST" class="row d-flex flex-column align-items-center" enctype="multipart/form-data">
                    @csrf
                    <div class="col-5">
                        <p class="text-center">Aqui você pode mudar o logotipo de sua guild.</p>
                        <label class="border p-3 col-5 d-block mx-auto position-relative" for="formFile">
                            <label id="edit" type="button" class="btn position-absolute top-0 start-0 m-0" for="formFile" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alterar Logo"><i class="fas fa-edit"></i></label>
                            <button id="close" type="button" class="btn position-absolute top-0 end-0 m-0" style="display: none;"><i class="fas fa-times"></i> </button>
                            <img src="{{ $guild->getGuildLogoLink() }}" data-original-image="{{ $guild->getGuildLogoLink() }}" class="rounded mx-auto d-block mb-2" id="img-preview" alt="{{ $guild->getName() }}" height="80" width="80">
                            <label id="file-label" class="text-center d-block mx-auto" for="formFile">Logo Atual</label>
                            <input class="form-control d-none" type="file" name="newlogo" id="formFile" accept=".jpg, .png, .gif">
                        </label>
                        <p class="text-center mt-2 mb-0">Apenas jpg, gif, png, bmp fotos. Máx tamanho: {{ config('otserver.site.guild_image_size_kb') }} KB</span>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-center">
                <button id="submitGuild" type="submit" class="sbutton-blue">Nova logomarca</button>
                <a href="{{ route('guilds.show', ['guildId' => $guild->getId() ]) }}" class="sbutton-red">Cancelar</a>
            </div>
            @endif
        </div>
    </div>
  </div>
@endsection
