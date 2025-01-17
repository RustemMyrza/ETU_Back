@extends('adminlte::page')

@section('title', 'Блог ректора')

@section('content_header')
    <h1>Блог ректора</h1>
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
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>E-mail</th>
                    <th>Дата</th>
                    <th>Вопрос</th>
                    <th>Ответ</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($question as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($item->name . ' ' . $item->surname, 50) }}</td>
                            <td>{{ Str::limit($item->phone, 50) }}</td>
                            <td>{{ Str::limit($item->email, 50) }}</td>
                            <td>{{ Str::limit($item->updated_at, 50) }}</td>
                            <td>{{ Str::limit($item->question, 50) }}</td>
                            <td>{{ $item->answer ? Str::limit($item->answer, 50) : 'Не отвечено' }}</td>
                            <td>
                            <a href="{{ url('/admin/rectorsBlogQuestion/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('/admin/rectorsBlogQuestion/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Ответить
                                    </button>
                                </a>

                                <form method="POST" action="{{ url('/admin/rectorsBlogQuestion' . '/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $question->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
