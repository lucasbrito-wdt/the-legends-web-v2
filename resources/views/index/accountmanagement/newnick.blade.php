@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Novo Nome</div>
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
                    @foreach($errors->all() as $message)
                        <x-notification message="{{ $message }}" isAutoClose="false" class="mt-3 mb-3"/>
                    @endforeach
                    <form id="submitForm" action="{{ route('accountmanagement.newnick', ['name' => $name]) }}" method="POST" class="col-5 mx-auto">
                        @csrf
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" id="name" placeholder="Nome:" maxlength="29" value="{{ $name }}" disabled>
                            <label for="name">Nome:</label>
                        </div>
                        <div class="form-floating mb-1">
                            <input type="text" name="name_new" class="form-control" id="name_new" placeholder="Novo Nome:" maxlength="29">
                            <label for="name_new">Novo Nome:</label>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button type="submit" class="sbutton-blue" onclick="submitForm()">Alterar Nome</button>
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
