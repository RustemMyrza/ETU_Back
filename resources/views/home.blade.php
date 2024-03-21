@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Страница администрирования</h1>
@stop

@section('content')
    <p>Вы удачно авторизовались.</p>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
