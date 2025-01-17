@extends('adminlte::page')

@section('title', 'Содержание')

@section('content_header')
    <h1>{{$schoolName . '(' . $specialtyName . ' страница)'}}</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/bachelorSchool/' . $schoolId . '/specialty/') }}" class="btn btn-danger btn-sm" title="Добавить новый блок">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
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
                    @foreach($bachelorSpecialtyPage as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title ? Str::limit($translatesData->find($item->title)->ru, 50) : '' }}</td>
                                <td>{{ $item->content ? Str::limit($translatesData->find($item->content)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <!-- {{ url("$item->image")}} -->
                                <td>
                                <a href="{{ url('/admin/bachelorSchool/' . $schoolId . '/specialty/'. $specialtyId .'/page/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/bachelorSchool/' . $schoolId . '/specialty/'. $specialtyId .'/page/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>
                                </td>
                            </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $bachelorSpecialtyPage->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
