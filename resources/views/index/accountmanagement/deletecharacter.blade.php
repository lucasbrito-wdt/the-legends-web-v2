@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Excluír Personagem</div>
            @if($success)
                <div class="main-content">
                    @foreach($errors->all() as $message)
                    <p class="text-center m-0">{{ $message }}</p>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
            @else
                <div class="main-content">
                    <p class="mb-2 text-center">Para eliminar esse personagem digite sua senha e clique em <b>"Excluír Personagem"</b>.</p>
                    <p class="mb-2 text-center">Você pode desfazer a exclusão do personagem nos primeiros <b>2 meses (60 dias)</b> após a eliminação.</p>
                    <p class="mb-2 text-center">Após este tempo, o personagem é eliminado para o bem e não pode mais ser restaurado!</p>
                    @foreach($errors->all() as $message)
                        <x-notification message="{{ $message }}" isAutoClose="false" class="mt-3 mb-3"/>
                    @endforeach
                    <form id="submitForm" action="{{ route('accountmanagement.deletecharacter', ['name' => $name]) }}" method="post" class="col-5 mx-auto">
                        @csrf
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" id="name" placeholder="Nome Completo:" maxlength="50" value="{{ $name }}" disabled>
                            <label for="name">Nome:</label>
                        </div>
                        <div class="form-floating mb-1">
                            <input type="password" class="form-control" id="password" placeholder="Senha:"
                            name="password" maxlength="29">
                            <label for="password">Senha:</label>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button type="submit" class="sbutton-blue" onclick="submitForm()">Excluír Personagem</button>
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        const submitForm = () => {
            document.getElementById("submitForm").submit();
        }
    </script>
@endpush
