@extends('adminlte::page')

@section('title', 'Центр карьеры')

@section('content_header')
    <h1>Центр карьеры</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
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
                    @foreach($careerCenterPage as $item)
                        @if($item->getContent != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title ? Str::limit($translatesData->find($item->title)->ru, 50) : '' }}</td>
                                <td>{{ $item->content ? Str::limit($translatesData->find($item->content)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <td>
                                <a href="{{ url('/admin/careerCenterPage/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/careerCenterPage/' . $item->id . '/edit') }}" title="Редактировать блок">
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
            <div class="pagination-wrapper"> {!! $careerCenterPage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
