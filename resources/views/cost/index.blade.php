@extends('adminlte::page')

@section('title', 'Стоимость')

@section('content_header')
    <h1>Стоимость</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/cost/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>

        <form method="GET" action="{{ url('/admin/cost') }}" accept-charset="UTF-8"
              class="form-inline my-2 my-lg-0 float-right" role="search">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Поиск..."
                       value="{{ request('search') }}">
                <span class="input-group-append">
                    <button class="btn btn-secondary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>

        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Группа образовательных программ</th>
                    <th>I курс</th>
                    <th>II курс</th>
                    <th>III курс</th>
                    <th>IV курс</th>
                    <th>Общая стоимость</th>
                    <th>Форма обучения</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($cost as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->program ? Str::limit($translatesData->find($item->program)->ru, 50) : '' }}</td>
                            <td>{{ $item->first ? Str::limit($item->first, 50) : '' }}</td>
                            <td>{{ $item->second ? Str::limit($item->second, 50) : '' }}</td>
                            <td>{{ $item->third ? Str::limit($item->third, 50) : '' }}</td>
                            <td>{{ $item->fourth ? Str::limit($item->fourth, 50) : '' }}</td>
                            <td>{{ $item->total ? Str::limit($item->total, 50) : '' }}</td>
                            <td>{{ $item->type ? Str::limit($formEducation[$item->type], 50) : '' }}</td>
                            <td>
                            <a href="{{ url('/admin/cost/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('/admin/cost/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>

                                <form method="POST" action="{{ url('/admin/cost' . '/' . $item->id) }}"
                                    accept-charset="UTF-8" style="display:inline">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-danger btn-sm" title="Удалить блок"
                                            onclick="return confirm(&quot;Удалить?&quot;)"><i class="fa fa-trash-alt"
                                                                                            aria-hidden="true"></i>
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $cost->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
