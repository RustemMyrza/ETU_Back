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
                    <tr><th> Название </th><td> {{ $cost->getName->ru }} </td></tr>
                    <tr>
                        <th> Таблица </th>
                        <td>
                            @if($cost->image != null)
                                <img src=" {{ url($cost->image) }} " alt=" {{ url($cost->image) }} " width="300">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Тип
                        </th>
                        <td>
                            @if($item->type == 1)
                                'Бакалавриат'
                            @else
                                'Магистратура'
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
