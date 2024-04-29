@extends('adminlte::page')

@section('title', 'Скидки и льготы для студентов-отличников учебы')

@section('content_header')
    <h1>Скидки и льготы для студентов-отличников учебы</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/honorsStudentDiscount/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Категория</th>
                    <th>Размер скидки %</th>
                    <th>Примечание</th>
                    <th>GPA</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($honorsStudentDiscount as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->category ? Str::limit($translatesData->find($item->category)->ru, 50) : '' }}</td>
                            <td>{{ $item->amount ? Str::limit($item->amount, 50) : '' }}</td>
                            <td>{{ $item->note ? Str::limit($translatesData->find($item->note)->ru, 50) : '' }}</td>
                            <td>{{ $item->gpa ? Str::limit($item->gpa, 50) : '' }}</td>
                            <td>
                            <a href="{{ url('/admin/honorsStudentDiscount/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('/admin/honorsStudentDiscount/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>

                                <form method="POST" action="{{ url('/admin/honorsStudentDiscount' . '/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $honorsStudentDiscount->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
