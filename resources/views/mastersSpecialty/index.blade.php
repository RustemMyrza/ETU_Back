@extends('adminlte::page')

@section('title', 'Специальности магистратуры')

@section('content_header')
    <h1>Специальности магистратуры</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('admin/mastersSpecialty/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
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
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mastersSpecialty as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->getName ? Str::limit($item->getName->ru, 50) : '' }}</td>
                            <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}"></td>
                            <td>
                            <a href="{{ url('admin/mastersSpecialty/' . $item->id . '/meta') }}" title="Метаданные"><button class="btn btn-secondary btn-sm"><i class="fa fa-info" aria-hidden="true"></i> Метаданные</button></a>
                            <a href="{{ url('admin/mastersSpecialty/' . $item->id . '/page') }}" title="Страница"><button class="btn btn-secondary btn-sm"><i class="fa fa-align-center" aria-hidden="true"></i> Страница</button></a>
                            <a href="{{ url('admin/mastersSpecialty/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('admin/mastersSpecialty/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>

                                <form method="POST" action="{{ url('admin/mastersSpecialty' . '/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $mastersSpecialty->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
