@extends('adminlte::page')

@section('title', 'Главная страница')

@section('content_header')
    <h1>Главная страница</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/mainPageMeta') }}" class="btn btn-secondary btn-sm" title="Метаданные">
            <i class="fa fa-info" aria-hidden="true"></i> Метаданные
        </a>
        <a href="{{ url('/admin/youtube/') }}" class="btn btn-danger btn-sm" title="Youtube">
            <i class="fa fa-play" aria-hidden="true"></i> YouTube
        </a>
        <a href="{{ url('/admin/instagramLink/') }}" class="btn btn-info btn-sm" title="Instagram">
            <i class="fa fa-camera" aria-hidden="true"></i> Instagram
        </a>
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
                    @foreach($mainPage as $item)
                        @if($item->getContent != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title ? Str::limit($translatesData->find($item->title)->ru, 50) : '' }}</td>
                                <td>{{ $item->content ? Str::limit($translatesData->find($item->content)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <td>
                                <a href="{{ url('/admin/mainPage/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/mainPage/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>

                                    <form method="POST" action="{{ url('/admin/mainPage' . '/' . $item->id) }}"
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
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $mainPage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
