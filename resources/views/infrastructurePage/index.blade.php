@extends('adminlte::page')

@section('title', 'Инфраструктура')

@section('content_header')
    <h1>Инфраструктура</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/infrastructurePageMeta') }}" class="btn btn-secondary btn-sm" title="Метаданные">
            <i class="fa fa-info" aria-hidden="true"></i> Метаданные
        </a>
        <a href="{{ url('/admin/infrastructureSlider') }}" class="btn btn-info btn-sm" title="Слайдер">
            <i class="fa fa-clone" aria-hidden="true"></i> Слайдер
        </a>
        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Заголовок</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($infrastructurePage as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->title ? Str::limit($item->getTitle->ru, 50) : '' }}</td>
                            <td>
                            <a href="{{ url('/admin/infrastructure/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('/admin/infrastructure/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $infrastructurePage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
