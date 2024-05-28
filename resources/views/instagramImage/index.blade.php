@extends('adminlte::page')

@section('title', 'Изображение Instagram')

@section('content_header')
    <h1>Изображение Instagram</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/instagramImage/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <a href="{{ url('/admin/instagramLink') }}" class="btn btn-primary btn-sm" title="Ссылка Instagram">
            <i class="fa fa-camera" aria-hidden="true"></i> Ссылка Instagram
        </a>
        <a href="{{ url('/admin/mainPage') }}" class="btn btn-primary btn-sm" title="Главная страница">
            <i class="fa fa-file" aria-hidden="true"></i> Главная страница
        </a>
        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($instagramImage))
                    @foreach($instagramImage as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <td>
                                    <a href="{{ url('/admin/instagramImage/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/instagramImage/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Редактировать</button>
                                    </a>
                                    <form method="POST" action="{{ url('/admin/instagramImage/' . $item->id) }}"
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
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $instagramImage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection