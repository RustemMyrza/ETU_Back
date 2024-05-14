@extends('adminlte::page')

@section('title', 'Содержание')

@section('content_header')
    <h1>{{$schoolName . ' (Специальности)'}}</h1>
@stop

@section('content')

    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/bachelorSchool/' . $schoolId . '/specialty/create') }}" class="btn btn-success btn-sm" title="Добавить новый блок">
            <i class="fa fa-plus" aria-hidden="true"></i> Добавить
        </a>
        <a href="{{ url('/admin/bachelorSchool') }}" class="btn btn-danger btn-sm" title="Добавить новый блок">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
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
                    @foreach($bachelorSchoolSpecialty as $item)
                        @if($item->getName != null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name ? Str::limit($translatesData->find($item->name)->ru, 50) : '' }}</td>
                                <td><img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->image ? url($item->image) : '' }}"></td>
                                <td>
                                <a href="{{ route('bachelorSpecialty.document.index', ['schoolId' => $schoolId, 'specialtyId' => $item->id]) }}" title="Документы"><button class="btn btn-secondary btn-sm"><i class="fa fa-book" aria-hidden="true"></i> Документы</button></a>
                                <a href="{{ route('specialty.page.index', ['schoolId' => $schoolId, 'specialtyId' => $item->id]) }}" title="Страница"><button class="btn btn-secondary btn-sm"><i class="fa fa-file" aria-hidden="true"></i> Страница</button></a>
                                <a href="{{ url('/admin/bachelorSchool/' . $schoolId . '/specialty/' . $item->id) }}" title="Посмотреть блок"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Просмотр</button></a>
                                    <a href="{{ url('/admin/bachelorSchool/' . $schoolId . '/specialty/' . $item->id . '/edit') }}" title="Редактировать блок">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"
                                                                                aria-hidden="true"></i> Редактировать
                                        </button>
                                    </a>

                                    <form method="POST" action="{{ url('/admin/bachelorSchool/' . $schoolId . '/specialty/' . $item->id . '/delete') }}" accept-charset="UTF-8" style="display:inline">
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
            <div class="pagination-wrapper"> {!! $bachelorSchoolSpecialty->appends(['search' => Request::get('search')])->render() !!} </div>
        </div>

    </div>
@endsection
