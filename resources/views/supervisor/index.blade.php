@extends('adminlte::page')

@section('title', 'Руководители')

@section('content_header')
    <h1>Руководители</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/supervisor/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>

        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>ФИО</th>
                    <th>Должность</th>
                    <th>Электронная почта</th>
                    <th>Номер телефона</th>
                    <th>Адрес</th>
                    <th>Фото</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($translatedData))
                    @foreach($supervisor as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name ? Str::limit($translatedData->find($item->name)->ru, 50) : '' }}</td>
                                <td>{{ $item->position ? Str::limit($translatedData->find($item->position)->ru, 50) : '' }}</td>
                                <td>{{ $item->email ? Str::limit($item->email, 50) : '' }}</td>
                                <td>{{ $item->phone ? Str::limit($item->phone, 50) : '' }}</td>
                                <td>{{ $item->address ? Str::limit($translatedData->find($item->address)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}" width="200px;"></td>
                                <td>
                                <a href="{{ url('/admin/supervisor/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/supervisor/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>

                                    <form method="POST" action="{{ url('/admin/supervisor' . '/' . $item->id) }}"
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
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $supervisor->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
