@extends('errors.minimal')

@section('title', __($title ?? "Error - ". __($code ?? 0)))
@section('code', __($code ?? 0))
@section('message')
{!! $message ?? 'Ocorreu um erro.' !!}
@endsection
