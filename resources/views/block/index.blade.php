@extends('adminlte::page')

@section('title', 'Редактирование блока')

@section('content_header')
    @if(isset($type))
        @if($type == 'about')
            <h1>Блок о компании</h1>
        @elseif($type === 'service')
            <h1>Блок услуги</h1>
        @elseif($type === 'analytic')
            <h1>Блок аналитика</h1>
        @elseif($type === 'callback')
            <h1>Блок свяжитесь с нами</h1>
        @elseif($type === 'we_offer')
            <h1>Блок мы предлагаем</h1>
        @elseif($type === 'work_principles')
            <h1>Принципы работы</h1>
        @elseif($type === 'questions')
            <h1>Вопросы</h1>
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
        <form method="POST" action="{{ url('/admin/block') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                <label for="title" class="control-label">{{ 'Заголовок' }}</label>
                <input class="form-control" name="title" type="text" id="title" value="{{ isset($block->title) ? $block->title : ''}}" >
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group {{ $errors->has('sub_title') ? 'has-error' : ''}}" @if($type != 'about') hidden @endif>
                <label for="title" class="control-label">{{ 'Подзаголовок' }}</label>
                <input class="form-control" name="content[sub_title]" type="text" id="sub_title" value="{{ isset($block->sub_title) ? $block->sub_title : ''}}" >
                {!! $errors->first('sub_title', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
                <label for="content" class="control-label">{{ 'Описание' }}</label>
                <textarea class="form-control" rows="5" name="content[content]" type="textarea" id="content" >{{ isset($block->content) ? $block->content : ''}}</textarea>
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group {{ $errors->has('block_type') ? 'has-error' : ''}}" hidden>
                <label for="block_type" class="control-label">{{ 'Тип блока' }}</label>
                <input class="form-control" name="block_type" type="text" id="block_type" value="{{ isset($type) ? $type : ''}}" >
                {!! $errors->first('block_type', '<p class="help-block">:message</p>') !!}
            </div>


            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="{{ 'Сохранить' }}">
            </div>


        </form>
    </div>
@endsection
