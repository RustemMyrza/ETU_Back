@extends('adminlte::page')

@section('title', 'Просмотр блока')

@section('content_header')
    <h1>Просмотр блока</h1>
@stop

@section('content')

    <div class="card-body">

        <a href="{{ url('/admin/aboutUs') }}" title="Назад"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Назад</button></a>
        <a href="{{ url('/admin/aboutUs/' . $aboutUs->id . '/edit') }}" title="Редактировать блок"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Редактировать</button></a>

        <form method="POST" action="{{ url('admin/aboutUs' . '/' . $aboutUs->id) }}" accept-charset="UTF-8" style="display:inline">
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
                        <th>ID</th><td>{{ $aboutUs->id }}</td>
                    </tr>
                    <tr><th> Заголовок </th><td> {{ $translatedData['title']->ru }} </td></tr>
                    <tr><th> Описание </th><td> {{ $translatedData['content']->ru }} </td></tr>
                    <tr><th> Изображение </th><td><img src="{{\Config::get('constants.alias.cdn_url').$aboutUs->image}}" alt="url($aboutUs->image)" width="200px;"></td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
