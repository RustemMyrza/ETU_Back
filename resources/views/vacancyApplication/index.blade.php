@extends('adminlte::page')

@section('title', 'Содержание')

@section('content_header')
    <h1>{{$vacancyPosition}}</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/vacancy/' . $vacancyId . '/applications/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <a href="{{ url('/admin/vacancy') }}" class="btn btn-danger btn-sm" title="Добавить новый блок">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
        </a>

        <form method="GET" action="{{ url('/admin/vacancy/' . $vacancyId . '/applications') }}" accept-charset="UTF-8"
              class="form-inline my-2 my-lg-0 float-right" role="search">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Поиск..."
                       value="{{ request('search') }}">
                <span class="input-group-append">
                    <button class="btn btn-secondary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>

        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>ФИО</th>
                    <th>Номер телефона</th>
                    <th>Электронная почта</th>
                    <th>Резюме</th>
                    <th>Мотивационное письмо</th>
                    <th>Документы об образований</th>
                    <th>Список рекомендодателей</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @if(isset($vacancyApplication))
                        @foreach($vacancyApplication as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name ? Str::limit($item->name, 50) : '' }}</td>
                                <td>{{ $item->phone ? Str::limit($item->phone, 50) : '' }}</td>
                                <td>{{ $item->email ? Str::limit($item->email, 50) : '' }}</td>
                                <td><a href="{{ $item->summary ? url($item->summary) : '' }}">{{ $item->summary ? url($item->summary) : '' }}</a></td>
                                <td><a href="{{ $item->letter ? url($item->letter) : '' }}">{{ $item->letter ? url($item->letter) : '' }}</a></td>
                                <td><a href="{{ $item->education ? url($item->education) : '' }}">{{ $item->education ? url($item->education) : '' }}</a></td>
                                <td><a href="{{ $item->recommender ? url($item->recommender) : '' }}">{{$item->recommender ? url($item->recommender) : '' }}</a></td>
                                <!-- {{ url("$item->image")}} -->
                                <td>
                                <a href="{{ url('/admin/vacancy/' . $vacancyId . '/applications/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>


                                    <form method="POST" action="{{ url('/admin/vacancy/' . $vacancyId . '/applications' . '/' . $item->id . '/delete') }}" accept-charset="UTF-8" style="display:inline">
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
            <div class="pagination-wrapper"> {!! $vacancyApplication->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
