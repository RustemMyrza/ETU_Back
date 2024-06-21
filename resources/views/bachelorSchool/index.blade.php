@extends('adminlte::page')

@section('title', 'Школы Бакалавриата')

@section('content_header')
    <h1>Школы Бакалавриата</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/bachelorPageMeta') }}" class="btn btn-secondary btn-sm" title="Метаданные">
            <i class="fa fa-info" aria-hidden="true"></i> Метаданные
        </a>
        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($translatesData))
                    @foreach($bachelorSchool as $item)
                        @if($item->getName != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name ? Str::limit($translatesData->find($item->name)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <td>
                                <a href="{{ url('admin/bachelorSchool/' . $item->id . '/meta') }}" title="Метаданные"><button class="btn btn-secondary btn-sm"><i class="fa fa-info" aria-hidden="true"></i> Метаданные</button></a>
                                <a href="{{ url('admin/bachelorSchool/' . $item->id . '/page') }}" title="Посмотреть блок"><button class="btn btn-secondary btn-sm"><i class="fa fa-file" aria-hidden="true"></i> Страница</button></a>
                                <a href="{{ url('admin/bachelorSchool/' . $item->id . '/specialty') }}" title="Посмотреть блок"><button class="btn btn-secondary btn-sm"><i class="fa fa-university" aria-hidden="true"></i> Специальности</button></a>
                                <a href="{{ url('admin/bachelorSchool/' . $item->id . '/educator') }}" title="Посмотреть блок"><button class="btn btn-secondary btn-sm"><i class="fa fa-user-circle" aria-hidden="true"></i> Педагоги</button></a>
                                <a href="{{ url('/admin/bachelorSchool/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/bachelorSchool/' . $item->id . '/edit') }}" title="Редактировать блок">
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
            <div class="pagination-wrapper"> {!! $bachelorSchool->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
