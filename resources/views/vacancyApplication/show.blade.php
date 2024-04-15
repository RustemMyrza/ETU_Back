@extends('adminlte::page')

@section('title', 'Просмотр блока')

@section('content_header')
    <h1>Просмотр блока</h1>
@stop

@section('content')

    <div class="card-body">

        <a href="{{ url('/admin/vacancy/' . $vacancyId . '/applications') }}" title="Назад"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Назад</button></a>

        <form method="POST" action="{{ url('/admin/vacancy/' . $vacancyId . '/applications/' . $vacancyApplication->id . '/delete') }}" accept-charset="UTF-8" style="display:inline">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <button type="submit" class="btn btn-danger btn-sm" title="Удалить блок" onclick="return confirm(&quot;Удалить?&quot;)"><i class="fa fa-trash-alt" aria-hidden="true"></i> Удалить</button>
        </form>
        <br/>
        <br/>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                        <th>ID</th><td>{{ $vacancyApplication->id }}</td>
                    </tr>
                    <tr><th> ФИО </th><td> {{ $vacancyApplication->name }} </td></tr>
                    <tr><th> Номер телефона </th><td> {{ $vacancyApplication->phone }} </td></tr>
                    <tr><th> Электронная почта </th><td> {{ $vacancyApplication->email }} </td></tr>
                    <tr><th> Резюме </th><td><a href="{{ $vacancyApplication->summary ? url($vacancyApplication->summary) : '' }}">{{ url($vacancyApplication->summary) }}</a></td></tr>
                    <tr><th> Мотивационное письмо </th><td><a href="{{ $vacancyApplication->letter ? url($vacancyApplication->letter) : '' }}">{{ url($vacancyApplication->letter) }}</a></td></tr>
                    <tr><th> Документы об образовании </th><td><a href="{{ $vacancyApplication->education ? url($vacancyApplication->education) : '' }}">{{ url($vacancyApplication->education) }}</a></td></tr>
                    <tr><th> Список рекомендодателей </th><td><a href="{{ $vacancyApplication->recommender ? url($vacancyApplication->recommender) : '' }}">{{ url($vacancyApplication->recommender) }}</a></td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
