@extends('adminlte::page')

@section('title', 'Содержание')

@section('content_header')
    <h1>{{$newsName}}</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/news/' . $newsId . '/content/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <a href="{{ url('/admin/news') }}" class="btn btn-danger btn-sm" title="Добавить новый блок">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
        </a>

        <form method="GET" action="{{ url('/admin/news/' . $newsId . '/content') }}" accept-charset="UTF-8"
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
                    <th>Заголовок</th>
                    <th>Описание</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($translatesData))
                {
                    @foreach($newsContent as $item)
                        @if($item->getContent != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->header ? Str::limit($translatesData->find($item->header)->ru, 50) : '' }}</td>
                                <td>{{ $item->content ? Str::limit($translatesData->find($item->content)->ru, 50) : '' }}</td>
                                <td><img src="{{ url("$item->image")}}" alt="" width="200px;"></td>
                                <!-- {{ url("$item->image")}} -->
                                <td>
                                <a href="{{ url('/admin/news/' . $newsId . '/content/create/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/news/' . $newsId . '/content/create/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>

                                    <form method="POST" action="{{ url('/admin/news/' . $newsId . '/content' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
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
                        @endif
                    @endforeach
                    }
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $newsContent->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
