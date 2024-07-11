@extends('adminlte::page')

@section('title', 'Новости')

@section('content_header')
    <h1>Новости</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('admin/news/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>

        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Дата</th>
                    <th>Изображение</th>
                </tr>
                </thead>
                <tbody>
                @foreach($news as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->getName ? Str::limit($item->getName->ru, 50) : '' }}</td>
                            <td>{{ $item->date ? $item->date : '' }}</td>
                            <td>
                                <img src="{{ url($item->background_image) }}" alt="{{$item->background_image}}" width="200px">
                            </td>
                            <td>
                            <a href="{{ url('admin/news/' . $item->id . '/meta') }}" title="Метаданные"><button class="btn btn-secondary btn-sm"><i class="fa fa-info" aria-hidden="true"></i> Метаданные</button></a>
                            <a href="{{ url('admin/news/' . $item->id . '/slider') }}" title="Слайдер"><button class="btn btn-warning btn-sm"><i class="fa fa-clone" aria-hidden="true"></i> Слайдер</button></a>
                            <a href="{{ url('admin/news/' . $item->id . '/content') }}" title="Содержимое"><button class="btn btn-secondary btn-sm"><i class="fa fa-align-center" aria-hidden="true"></i> Содержимое</button></a>
                            <a href="{{ url('admin/news/' . $item->id . '/document') }}" title="Документы"><button class="btn btn-secondary btn-sm"><i class="fa fa-file" aria-hidden="true"></i> Документы</button></a>
                            <a href="{{ url('admin/news/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('admin/news/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>

                                <form method="POST" action="{{ url('admin/news' . '/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $news->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
