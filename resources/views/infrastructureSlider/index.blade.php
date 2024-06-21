@extends('adminlte::page')

@section('title', 'Инфраструктура (Слайдер)')

@section('content_header')
    <h1>Инфраструктура (Слайдер)</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/infrastructure/') }}" class="btn btn-danger btn-sm" title="Назад">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
        </a>
        <a href="{{ url('/admin/infrastructureSlider/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Заголовок</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($infrastructureSlider as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->title ? Str::limit($item->getTitle->ru, 50) : '' }}</td>
                            <td>
                                @foreach(json_decode($item->images) as $sliderImage)
                                    <img src="{{ url($sliderImage)}}" alt="{{ url($sliderImage) }}" width="200px;" style="display: flex; flex-direction:row">
                                @endforeach
                            </td>
                            <td>
                            <a href="{{ url('/admin/infrastructureSlider/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('/admin/infrastructureSlider/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>
                                <form method="POST" action="{{ url('/admin/infrastructureSlider/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $infrastructureSlider->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
