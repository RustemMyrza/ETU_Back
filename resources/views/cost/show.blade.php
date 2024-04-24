@extends('adminlte::page')

@section('title', 'Просмотр блока')

@section('content_header')
    <h1>Просмотр блока</h1>
@stop

@section('content')

    <div class="card-body">

        <a href="{{ url('/admin/cost') }}" title="Назад"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Назад</button></a>
        <a href="{{ url('/admin/cost/' . $cost->id . '/edit') }}" title="Редактировать блок"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Редактировать</button></a>

        <form method="POST" action="{{ url('admin/cost' . '/' . $cost->id) }}" accept-charset="UTF-8" style="display:inline">
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
                        <th>ID</th><td>{{ $cost->id }}</td>
                    </tr>
                    <tr><th> Группа образовательных программ </th><td> {{ $translatedProgram->ru }} </td></tr>
                    <tr><th> I курс </th><td> {{ $cost->first }} </td></tr>
                    <tr><th> II курс </th><td> {{ $cost->second }} </td></tr>
                    <tr><th> III курс </th><td> {{ $cost->third }} </td></tr>
                    <tr><th> IV курс </th><td> {{ $cost->fourth }} </td></tr>
                    <tr><th> V курс </th><td> {{ $cost->fifth }} </td></tr>
                    <tr><th> Общая стоимость </th><td> {{ $cost->total }} </td></tr>
                    <tr><th> Форма обучения </th><td> {{ $cost->type ? $formEducation[$cost->type] : '' }} </td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
