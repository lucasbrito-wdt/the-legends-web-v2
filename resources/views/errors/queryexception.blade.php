@extends('errors.minimal')

@section('title', __('Query Exception'))
@section('code')
    <p style="margin: 5px;">SQLSTATE</p>
    <p style="margin: 0px;">{{ $erroCode }}</p>
@endsection
@section('message')
    <table>
        <tr>
            <td width="150" style="text-align: end">Query: </td>
            <td><b>{{ $query }}</b></td>
        </tr>
        <tr>
            <td width="150" style="text-align: end">Driver code: </td>
            <td>{{ $driverCode }}</td>
        </tr>
        <tr>
            <td width="150" style="text-align: end">Error message: </td>
            <td>{{ $message }}</td>
        </tr>
    </table>
@endsection
