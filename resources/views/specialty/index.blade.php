@extends('adminlte::page')

@section('title', 'Специальности')

@section('content_header')
    <h1>Специальности</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('admin/specialty/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        
        <form method="GET" action="{{ url('admin/specialty/') }}" accept-charset="UTF-8"
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
                    <th>Код</th>
                    <th>Названия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($specialty as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code ? Str::limit($item->code, 50) : '' }}</td>
                            <td>{{ $item->getName ? Str::limit($item->getName->ru, 50) : '' }}</td>
                            <td>
                            <a href="{{ url('admin/specialty/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('admin/specialty/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>

                                <form method="POST" action="{{ url('admin/specialty' . '/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $specialty->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
