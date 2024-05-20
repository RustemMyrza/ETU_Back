@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <h1>{{ $pageHeader }}</h1>
@stop
@section('content')
    <div class="card-body">
        <a href="{{ $backUrl }}" class="btn btn-danger btn-sm" title="Назад">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Назад
        </a>
        @include('flash-message')
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <button onclick="addKeywordInput()" class="btn btn-success btn-sm" title="Добавить ключевое слово"><i class="fa fa-plus" aria-hidden="true"></i>Добавить ключевое слово</button>
        <form method="POST" action="{{ $formAction }}" accept-charset="UTF-8" class="form-horizontal"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="tab-content col-md-12" id="custom-tabs-one-tabContent">
                <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                    <label for="name" class="control-label">{{ 'Заголовок' }}</label>
                    <input class="form-control" name="name" type="text" id="name"
                            value="{{ isset($metaData->name) ? $metaData->name : ''}}" required>
                </div>

                <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                    <label for="description" class="control-label">{{ 'Описание' }}</label>
                    <textarea class="form-control" name="description" id="description" required>{{ isset($metaData->description) ? $metaData->description : ''}}</textarea>
                </div>
                <div id="keywords-container">
                @if(isset($metaData->keyword))
                    @foreach(explode(', ', $metaData->keyword) as $key => $value)
                        @if($value != '')
                            <div class="form-group {{ $errors->has('keyword') ? 'has-error' : ''}}">
                                <label for="{{ 'keyword ' . strval($key + 1) }}" class="control-label">{{ 'Ключевое слово ' . strval($key + 1) }}</label>
                                <input class="form-control" name="{{ 'keyword ' . strval($key + 1) }}" type="text" id="{{ 'keyword ' . strval($key + 1) }}"
                                        value="{{ $value }}">
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="form-group {{ $errors->has('keyword') ? 'has-error' : ''}}">
                        <label for="keyword_1" class="control-label">{{ 'Ключевое слово 1' }}</label>
                        <input class="form-control" name="keyword_1" type="text" id="keyword_1" required>
                    </div>
                @endif
                </div>
            </div>

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="{{ 'Сохранить' }}">
            </div>
        </form>
@endsection

<script>
    let keywordCounter = 1; // Начальное значение счетчика ключевых слов

    function addKeywordInput() {
        const container = document.getElementById('keywords-container');
        if (keywordCounter <= 10) {
            // Создаем новый div элемент
            const newDiv = document.createElement('div');
            newDiv.className = 'form-group';

            // Создаем label элемент
            const newLabel = document.createElement('label');
            newLabel.setAttribute('for', `keyword_${keywordCounter + 1}`);
            newLabel.className = 'control-label';
            newLabel.textContent = `Ключевые слово ${keywordCounter + 1}`;

            // Создаем input элемент
            const newInput = document.createElement('input');
            newInput.className = 'form-control';
            newInput.type = 'text';
            newInput.name = `keyword_${keywordCounter + 1}`;
            newInput.id = `keyword_${keywordCounter + 1}`;


            // Добавляем текст ошибки
            const errorText = document.createElement('p');
            errorText.className = 'help-block';
            errorText.textContent = '';

            // Добавляем элементы в новый div
            newDiv.appendChild(newLabel);
            newDiv.appendChild(newInput);
            newDiv.appendChild(errorText);

            // Добавляем новый div в контейнер
            container.appendChild(newDiv);
            keywordCounter++;
        } else {
            alert('Максимальное количество ключевых слов достигнуто');
        }
    }

</script>

