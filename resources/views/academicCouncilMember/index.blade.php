@extends('adminlte::page')

@section('title', 'Члены ученого совета')

@section('content_header')
    <h1>Члены ученого совета</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/academicCouncilMember/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>

        <form method="GET" action="{{ url('/admin/academicCouncilMember') }}" accept-charset="UTF-8"
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
                    <th>Описание</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($translatesData))
                    @foreach($academicCouncilMember as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name ? Str::limit($translatesData->find($item->name)->ru, 50) : '' }}</td>
                            <td>{{ $item->description ? Str::limit($translatesData->find($item->description)->ru, 50) : '' }}</td>
                            <td>
                            <a href="{{ url('/admin/academicCouncilMember/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                <a href="{{ url('/admin/academicCouncilMember/' . $item->id . '/edit') }}" title="Редактировать блок">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                            aria-hidden="true"></i> Редактировать
                                    </button>
                                </a>

                                <form method="POST" action="{{ url('/admin/academicCouncilMember' . '/' . $item->id) }}"
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
            <div class="pagination-wrapper"> {!! $academicCouncilMember->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
