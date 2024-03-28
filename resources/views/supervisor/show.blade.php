@extends('adminlte::page')

@section('title', 'Просмотр блока')

@section('content_header')
    <h1>Просмотр блока</h1>
@stop

@section('content')

    <div class="card-body">

        <a href="{{ url('/admin/supervisor') }}" title="Назад"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Назад</button></a>
        <a href="{{ url('/admin/supervisor/' . $supervisor->id . '/edit') }}" title="Редактировать блок"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Редактировать</button></a>

        <form method="POST" action="{{ url('admin/supervisor' . '/' . $supervisor->id) }}" accept-charset="UTF-8" style="display:inline">
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
                        <th>ID</th><td>{{ $supervisor->id }}</td>
                    </tr>
                    <tr><th> ФИО </th><td> {{ $translatedData['name']->ru }} </td></tr>
                    <tr><th> Должность </th><td> {{ $translatedData['position']->ru }} </td></tr>
                    <tr><th> Электронная почта </th><td> {{ $supervisor->email }} </td></tr>
                    <tr><th> Номер телефона </th><td> {{ $supervisor->phone }} </td></tr>
                    <tr><th> Адрес </th><td> {{ $translatedData['address']->ru }} </td></tr>
                    <tr><th> Фото </th><td><img src="{{\Config::get('constants.alias.cdn_url').$supervisor->image}}" alt="{{$supervisor->image}}" width="200px;"></td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
