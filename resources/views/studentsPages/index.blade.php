@extends('adminlte::page')

@section('title', 'Разделы для студентов')

@section('content_header')
    <h1>Разделы для студентов</h1>
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
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($studentsPages as $item)
                    @if($item->getName != null)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->getName ? Str::limit($item->getName->ru, 50) : '' }}</td>
                            <td>
                            <a href="{{ url('admin/students/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('admin/students/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $studentsPages->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
