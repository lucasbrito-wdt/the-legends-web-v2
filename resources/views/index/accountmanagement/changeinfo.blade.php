@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
                <div class="bg-title">Alterar Informação Pública</div>
                <div class="main-content">
                    <p class="mb-2 text-center">Aqui você pode dizer outros jogadores sobre si mesmo. Esta informação será apresentada juntamente com os dados de seus personagens. Se você não quiser preencher um determinado campo, apenas deixá-lo em branco.</p>
                    @foreach($errors->all() as $message)
                        <x-notification message="{{ $message }}" isAutoClose="false" class="mt-3 mb-3"/>
                    @endforeach
                    <form id="changeinfoForm" action="changeinfo" method="post" class="col-5 mx-auto">
                        @csrf
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" id="info_rlname" placeholder="Nome Completo:"
                            name="info_rlname" maxlength="50" value="{{ $account_rlname }}">
                            <label for="info_rlname">Nome Completo:</label>
                        </div>
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control" id="info_location" placeholder="Localização:"
                            name="info_location" maxlength="50" value="{{ $account_location }}">
                            <label for="info_location">Localização:</label>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mt-1">
                        <button type="submit" class="sbutton-blue" onclick="changeinfoForm()">Alterar Informação</button>
                        <a href="/accountmanagement" class="sbutton-red">Voltar</a>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        const changeinfoForm = () => {
            document.getElementById("changeinfoForm").submit();
        }
    </script>
@endpush
