<ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="custom-tabs-one-ru-tab" data-toggle="pill" href="#custom-tabs-one-ru" role="tab" aria-controls="custom-tabs-one-ru" aria-selected="true">Русский</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-tabs-one-en-tab" data-toggle="pill" href="#custom-tabs-one-en" role="tab" aria-controls="custom-tabs-one-en" aria-selected="false">Английский</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-tabs-one-kz-tab" data-toggle="pill" href="#custom-tabs-one-kz" role="tab" aria-controls="custom-tabs-one-kz" aria-selected="false">Казахский</a>
    </li>
</ul>

<div class="tab-content col-md-12" id="custom-tabs-one-tabContent">
    <div class="tab-pane active in ru-content" id="custom-tabs-one-ru" role="tabpanel" aria-labelledby="custom-tabs-one-ru-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="title[ru]" class="control-label">{{ 'Заголовок RU' }}</label>
            <input class="form-control" name="title[ru]" type="text" id="title_ru" value="{{ isset($translatedData['title']->ru) ? $translatedData['title']->ru : ''}}" >
            {!! $errors->first('content[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="title[en]" class="control-label">{{ 'Заголовок EN' }}</label>
            <input class="form-control" name="title[en]" type="text" id="title_en" value="{{ isset($translatedData['title']->en) ? $translatedData['title']->en : ''}}" >
            {!! $errors->first('content[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="title[kz]" class="control-label">{{ 'Заголовок KZ' }}</label>
            <input class="form-control" name="title[kz]" type="text" id="title_kz" value="{{ isset($translatedData['title']->kz) ? $translatedData['title']->kz : ''}}" >
            {!! $errors->first('content[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="tab-content col-md-12" id="custom-tabs-one-tabContent">
    <div class="tab-pane active in ru-content" id="custom-tabs-one-ru" role="tabpanel" aria-labelledby="custom-tabs-one-ru-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="content_ru" class="control-label">{{ 'Описание RU' }}</label>
            <!-- <input class="form-control" name="content[ru]" type="text" id="content_ru" value="{{ isset($translatedData['content']->ru) ? $translatedData['content']->ru : ''}}" > -->
            <textarea class="ckeditor_textarea" name="content[ru]" id="content[ru]" cols="30" rows="10">{{ isset($translatedData['content']->ru) ? $translatedData['content']->ru : ''}}</textarea>
            {!! $errors->first('content[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="content_ru" class="control-label">{{ 'Описание EN' }}</label>
            <!-- <input class="form-control" name="content[en]" type="text" id="content_ru" value="{{ isset($translatedData['content']->en) ? $translatedData['content']->en : ''}}" > -->
            <textarea class="ckeditor_textarea" name="content[en]" id="content[en]" cols="30" rows="10">{{ isset($translatedData['content']->en) ? $translatedData['content']->en : ''}}</textarea>
            {!! $errors->first('content[en]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="content_kz" class="control-label">{{ 'Описание KZ' }}</label>
            <!-- <input class="form-control" name="content[kz]" type="text" id="content_kz" value="{{ isset($translatedData['content']->kz) ? $translatedData['content']->kz : ''}}" > -->
            <textarea class="ckeditor_textarea" name="content[kz]" id="content[kz]" cols="30" rows="10">{{ isset($translatedData['content']->kz) ? $translatedData['content']->kz : ''}}</textarea>
            {!! $errors->first('content[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>


<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
    <label for="image" class="control-label">{{ 'Изображение' }}</label>
    <input class="form-control" name="image" type="file" id="image" value="{{ isset($medicalCarePage->image) ? $medicalCarePage->image : ''}}" >
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
@if (isset($medicalCarePage->image))
    <div class="form-group">
        <img src="{{ \Config::get('constants.alias.cdn_url').$medicalCarePage->image }}" alt="" width="300px;">
    </div>
@endif


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Обновить' : 'Создать' }}">
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Находим ссылки для изменения вкладок
    var ruLink = document.getElementById("custom-tabs-one-ru-tab");
    var enLink = document.getElementById("custom-tabs-one-en-tab");
    var kzLink = document.getElementById("custom-tabs-one-kz-tab");

    // Находим элементы, которые нужно изменить
    var ruContent = document.getElementsByClassName("ru-content");
    var enContent = document.getElementsByClassName("en-content");
    var kzContent = document.getElementsByClassName("kz-content");
    
    var allContent = document.getElementsByClassName("tab-pane");

    console.log(allContent);
    console.log(ruLink);
    console.log(enLink);
    console.log(kzLink);
    console.log(ruContent);
    console.log(enContent);
    console.log(kzContent);
    // Функция для изменения содержимого вкладок
    function changeContent(link, content, allContent) {
        link.addEventListener("click", function(event) {
            for (let i = 0; i < allContent.length; i++)
            {
                for (let j = 0; j < content.length; j++)
                {
                    if (allContent[i].classList.contains("in") && allContent[i] != content[j])
                    {
                        allContent[i].classList.remove("active", "in");
                    }
                    content[j].classList.add("active", "in");
                    content[j].classList.remove("fade");
                }
            }
            event.preventDefault();
        });
    }

    // Вызываем функцию для каждой ссылки
    changeContent(ruLink, ruContent, allContent);
    changeContent(enLink, enContent, allContent);
    changeContent(kzLink, kzContent, allContent);
});
</script>
<script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
<script>
     document.querySelectorAll('.ckeditor_textarea').forEach(function(element) {
        CKEDITOR.replace(element);
    });
</script>