@extends('adminlte::page')

@section('title', 'YouTube Видео')

@section('content_header')
    <h1>YouTube Видео</h1>
@stop
@section('content')
    <div class="card-body">
        @include('flash-message')
        <a href="{{ url('/admin/mainPage') }}" class="btn btn-primary btn-sm" title="Главная страница">
            <i class="fa fa-file" aria-hidden="true"></i> Главная страница
        </a>
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ url('/admin/youtube') }}" accept-charset="UTF-8" class="form-horizontal"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="tab-content col-md-12" id="custom-tabs-one-tabContent">
                <div class="form-group {{ $errors->has('link') ? 'has-error' : ''}}">
                    <label for="link" class="control-label">{{ 'Ссылка на видео' }}</label>
                    <input class="form-control" name="link" type="text" id="link" value="{{ isset($youtubeVideo->link) ? $youtubeVideo->link : ''}}">
                </div>
            </div>

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="{{ 'Сохранить' }}">
            </div>


        </form>
@endsection
