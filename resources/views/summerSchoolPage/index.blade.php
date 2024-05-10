@extends('adminlte::page')

@section('title', 'Летняя школа')

@section('content_header')
    <h1>Летняя школа</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/summerSchoolProgram') }}" class="btn btn-info btn-sm" title="Программа">
            <i class="fa fa-tasks" aria-hidden="true"></i> Программа
        </a>
        <a href="{{ url('/admin/summerSchoolDocument') }}" class="btn btn-info btn-sm" title="Документы">
            <i class="fa fa-file" aria-hidden="true"></i> Документы
        </a>
        <a href="{{ url('/admin/summerSchoolSlider') }}" class="btn btn-info btn-sm" title="Слайдер">
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
                    <th>Описание</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($translatesData))
                    @foreach($summerSchoolPage as $item)
                        @if($item->getContent != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title ? Str::limit($translatesData->find($item->title)->ru, 50) : '' }}</td>
                                <td>{{ $item->content ? Str::limit($translatesData->find($item->content)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <td>
                                <a href="{{ url('/admin/summerSchoolPage/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/summerSchoolPage/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $summerSchoolPage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
