@extends('adminlte::page')

@section('title', 'Добавление блока')

@section('content_header')
    <h1>Добавление блока</h1>
@stop

@section('content')

    <div class="card-body">
        <a href="{{ url('/admin/summerSchoolProgram') }}" title="Назад"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Назад</button></a>
        <br />
        <br />

        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ url('/admin/summerSchoolProgram') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}

            @include ('summerSchoolProgram.form', ['formMode' => 'create'])

        </form>

    </div>
@endsection
