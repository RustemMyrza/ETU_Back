@extends('adminlte::page')

@section('title', 'Страница')

@section('content_header')
    <h1>{{$mastersSpecialtyName}}</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/mastersSpecialty') }}" class="btn btn-danger btn-sm" title="Добавить новый блок">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
        </a>
        <a href="{{ url('/admin/mastersSpecialty/' . $mastersSpecialtyId . '/documents') }}" class="btn btn-primary btn-sm" title="Добавить новый блок">
            <i class="fa fa-file" aria-hidden="true"></i> Документы
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
                    @foreach($mastersSpecialtyPage as $item)
                        @if($item->getContent != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title ? Str::limit($translatesData->find($item->title)->ru, 50) : '' }}</td>
                                <td>{{ $item->content ? Str::limit($translatesData->find($item->content)->ru, 50) : '' }}</td>
                                <td><img src="{{ url("$item->image")}}" alt="{{ url("$item->image")}}" width="200px;"></td>
                                <!-- {{ url("$item->image")}} -->
                                <td>
                                <a href="{{ url('/admin/mastersSpecialty/' . $mastersSpecialtyId . '/page/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/mastersSpecialty/' . $mastersSpecialtyId . '/page/' . $item->id . '/edit') }}" title="Редактировать блок">
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
            <div class="pagination-wrapper"> {!! $mastersSpecialtyPage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
