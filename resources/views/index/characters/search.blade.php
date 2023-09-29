@extends('layout.index')
@section('title', 'Characters')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Characters</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Procurar Personagem:</div>
            <div class="main-content p-0">
                <form action="{{ route('searchcharacters.redirectWithParams') }}" enctype="multipart/form-data" class="list-group list-group-horizontal row m-0">
                    <li class="list-group-item col-10">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="name">
                            <label for="floatingInput">Procurar</label>
                        </div>
                    </li>
                    <li class="list-group-item col-2 d-flex justify-content-center align-items-center">
                        <button type="submit" class="sbutton-blue float-end">Procurar</button>
                    </li>
                </form>
            </div>
        </div>
    </div>
  </div>
@endsection
