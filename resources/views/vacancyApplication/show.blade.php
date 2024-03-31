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
                    <tr><th> Резюме </th><td><img src="{{url("$vacancyApplication->summary")}}" alt="{{ $vacancyApplication->summary }}" width="200px;"></td></tr>
                    <tr><th> Мотивационное письмо </th><td><img src="{{url("$vacancyApplication->letter")}}" alt="{{ $vacancyApplication->letter }}" width="200px;"></td></tr>
                    <tr><th> Документы об образовании </th><td><img src="{{url("$vacancyApplication->education")}}" alt="{{ $vacancyApplication->education }}" width="200px;"></td></tr>
                    <tr><th> Список рекомендодателей </th><td><img src="{{url("$vacancyApplication->recommender")}}" alt="{{ $vacancyApplication->recommender }}" width="200px;"></td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
