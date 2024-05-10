@extends('adminlte::page')

@section('title', 'Научные издания')

@section('content_header')
    <h1>Научные издания</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/scientificPublicationDocument') }}" class="btn btn-info btn-sm" title="Добавить новый блок">
            <i class="fa fa-file" aria-hidden="true"></i> Издания
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
                    @foreach($scientificPublicationPage as $item)
                        @if($item->getContent != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title ? Str::limit($translatesData->find($item->title)->ru, 50) : '' }}</td>
                                <td>{{ $item->content ? Str::limit($translatesData->find($item->content)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <td>
                                <a href="{{ url('/admin/scientificPublicationPage/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/scientificPublicationPage/' . $item->id . '/edit') }}" title="Редактировать блок">
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
            <div class="pagination-wrapper"> {!! $scientificPublicationPage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
