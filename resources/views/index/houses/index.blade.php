@extends('layout.index')
@section('title', 'Casas')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Casas: </span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Casas:</div>
            <div class="main-content">
                <form action="{{ route('houses.index') }}" method="POST">
                    @csrf
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <th>Town</th>
                            <th>Owner</th>
                            <th>Sort by</th>
                        </thead>
                        <tbody>
                            <tbody>
                                <tr>
                                    <td>
                                        @foreach (config('otserver.towns_list')[$world] as $town_id => $town_name)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="town" id="town-{{ $town_id }}" value="{{ $town_id }}" @if($town == (int)$town_id) checked @endif>
                                            <label class="form-check-label" for="town-{{ $town_id }}">{{ htmlspecialchars($town_name) }}</label>
                                        </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="owner" id="owner-all" value="2" @if($owner == "2") checked @endif>
                                            <label class="form-check-label" for="owner-all">Todos</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="owner" id="owner-empty" value="0" @if($owner == "0") checked @endif>
                                            <label class="form-check-label" for="owner-empty">Vazio</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="owner" id="town-rented" value="1" @if($owner == "1") checked @endif>
                                            <label class="form-check-label" for="town-rented">Alugado</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="order" id="order-name" value="name" @if($order == 'name') checked @endif>
                                            <label class="form-check-label" for="order-name">Nome</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="order" id="order-size" value="size" @if($order == 'size') checked @endif>
                                            <label class="form-check-label" for="order-size">Tamanho</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="order" id="order-price" value="price" @if($order == 'price') checked @endif>
                                            <label class="form-check-label" for="order-price">Preço</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <th colspan="3">
                                        <button type="submit" class="sbutton-blue mx-auto d-block">Procurar</button>
                                    </th>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered m-0">
                        <thead class="table-dark">
                            <th>*</th>
                            <th>Nome</th>
                            <th>Tamanho</th>
                            <th>Proprietário</th>
                            <th>Preço</th>
                            <th>Mundo</th>
                        </thead>
                        <tbody>
                            @foreach ($houses as $house)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ htmlspecialchars($house->getName()) }}</td>
                                <td>{{ $house->getSize() }} sqm</td>
                                @if($house->getOwner() != 0)
                                    @if(@is_object($owners[$house->getOwner()]))
                                        <td><a href="{{ route('characters.index', ['name' => urlencode($owners[$house->getOwner()]->getName())]) }}">{{ htmlspecialchars($owners[$house->getOwner()]->getName()) }}</a></td>
                                    @else
                                        <td>Dono da casa bugado</td>
                                    @endif
                                @else
                                    <td>Vazio</td>
                                @endif
                                <td>{{$house->getPrice()}} gold</td>
                                <td>{{ config('otserver.site.worlds')[$house->getWorldId()]}}</td>
                            </tr>
                            @endforeach
                            @if(!count($houses) > 0)
                                <tr>
                                    <td colspan="6" class="text-center">Sem casas com parâmetros selecionados.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
