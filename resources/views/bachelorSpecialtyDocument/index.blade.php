@extends('adminlte::page')

@section('title', 'Страница')

@section('content_header')
    <h1>{{ $specialtyName }} (Документы)</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url( 'admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . '/documents/create' ) }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <a href="{{ url(route( 'school.specialty.index', ['schoolId' => $schoolId] ) ) }}" class="btn btn-danger btn-sm" title="Добавить новый блок">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
        </a>

        <form method="GET" action="{{ url(route('bachelorSpecialty.document.index', ['schoolId' => $schoolId, 'specialtyId' => $specialtyId])) }}" accept-charset="UTF-8"
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
                    <th>Название</th>
                    <th>Документ</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($translatesData))
                    @foreach($document as $item)
                        @if($item->getName != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name ? Str::limit($translatesData->find($item->name)->ru, 50) : '' }}</td>
                                <td><a href="{{ $item->link ? url($item->link) : '' }}">{{$item->name ? Str::limit($translatesData->find($item->name)->ru, 50) : ''}}</a></td>
                                <!-- {{ url("$item->image")}} -->
                                <td>
                                <a href="{{ url(route('bachelorSpecialty.document.show', ['schoolId' => $schoolId, 'specialtyId' => $specialtyId, 'id' => $item->id])) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . '/documents/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>

                                    <form method="POST" action="{{ url('admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . '/documents/' . $item->id . '/delete') }}" accept-charset="UTF-8" style="display:inline">
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
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $document->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection