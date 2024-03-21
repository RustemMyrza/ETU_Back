@extends('adminlte::page')

@section('title', 'Редактирование страницы')

@section('content_header')
    @if(isset($slug))
        @if($slug === 'home')
            <h1>Главная страница</h1>
        @elseif ($slug == 'about')
            <h1>Страница о нас</h1>
        @elseif($slug === 'service')
            <h1>Страница услуги</h1>
        @elseif($slug === 'analytic')
            <h1>Страница аналитика</h1>
        @else
            <h1>Страница контакты</h1>
        @endif
    @endif
@stop

@section('content')
    <div class="card-body">

        @include('flash-message')

        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ url('/admin/pages') }}" accept-charset="UTF-8" class="form-horizontal"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                <label for="title" class="control-label">{{ 'Title' }}</label>
                <input class="form-control" name="title" type="text" id="title"
                       value="{{ isset($page->title) ? $page->title : ''}}">
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
            <input type="text" hidden value="{{ isset($slug) ? $slug : ''}}" name="slug">
            <div class="form-group {{ $errors->has('meta_title') ? 'has-error' : ''}}">
                <label for="meta_title" class="control-label">{{ 'Meta Title' }}</label>
                <input class="form-control" name="meta_title" type="text" id="meta_title"
                       value="{{ isset($page->meta_title) ? $page->meta_title : ''}}">
                {!! $errors->first('meta_title', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group {{ $errors->has('meta_description') ? 'has-error' : ''}}">
                <label for="meta_description" class="control-label">{{ 'Meta Description' }}</label>
                <textarea class="form-control" rows="5" name="meta_description" type="textarea"
                          id="meta_description">{{ isset($page->meta_description) ? $page->meta_description : ''}}</textarea>
                {!! $errors->first('meta_description', '<p class="help-block">:message</p>') !!}
            </div>

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="{{ 'Сохранить' }}">
            </div>


        </form>
        @endsection

        @section('js')
            <script> console.log('pages!'); </script>
@stop
