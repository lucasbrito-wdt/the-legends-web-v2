@extends('layout.index')
@section('title', 'Loja')
@push('scripts')
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Loja</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Bem Vindo a loja Online!</div>
            <div class="main-content">
                @foreach($errors->all() as $message)
                    <div class="text-center m-0">{!! $message !!}</div>
                @endforeach
            </div>
            @if(!$logged)
            <div class="d-flex justify-content-center mt-1">
                <a href="{{ route('accountmanagement.login') }}" class="sbutton-blue">Login</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
