@extends('adminlte::page')

@section('title', 'Вакансий')

@section('content_header')
    <h1>Вакансий</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/vacancy/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Должность</th>
                    <th>Описание</th>
                    <th>Дата</th>
                    <th>Опыт</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($vacancy as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->position ? Str::limit($item->position, 50) : '' }}</td>
                                <td>{{ $item->description ? Str::limit($item->description, 50) : '' }}</td>
                                <td>{{ $item->date ? Str::limit($item->date, 50) : '' }}</td>
                                <td>{{ $item->experience ? Str::limit($item->experience, 50) : '' }}</td>
                                <td>
                                <a href="{{ route('vacancy.application.index', ['vacancyId' => $item->id]) }}" title="Заявки"><button class="btn btn-secondary btn-sm"><i class="fa fa-align-center" aria-hidden="true"></i> Заявки</button></a>
                                <a href="{{ url('/admin/vacancy/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/vacancy/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>

                                    <form method="POST" action="{{ url('/admin/vacancy' . '/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $vacancy->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
