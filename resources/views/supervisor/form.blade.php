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
            <label for="name[ru]" class="control-label">{{ 'ФИО RU' }}</label>
            <input class="form-control" name="name[ru]" type="text" id="name_ru" value="{{ isset($translatedData['name']->ru) ? $translatedData['name']->ru : ''}}" >
            {!! $errors->first('name[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="name[en]" class="control-label">{{ 'ФИО EN' }}</label>
            <input class="form-control" name="name[en]" type="text" id="name_en" value="{{ isset($translatedData['name']->en) ? $translatedData['name']->en : ''}}" >
            {!! $errors->first('name[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="name[kz]" class="control-label">{{ 'ФИО KZ' }}</label>
            <input class="form-control" name="name[kz]" type="text" id="name_kz" value="{{ isset($translatedData['name']->kz) ? $translatedData['name']->kz : ''}}" >
            {!! $errors->first('name[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>


    <div class="tab-pane active in ru-content" id="custom-tabs-one-ru" role="tabpanel" aria-labelledby="custom-tabs-one-ru-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="position[ru]" class="control-label">{{ 'Должность RU' }}</label>
            <input class="form-control" name="position[ru]" type="text" id="position_ru" value="{{ isset($translatedData['position']->ru) ? $translatedData['position']->ru : ''}}" >
            {!! $errors->first('position[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="position[en]" class="control-label">{{ 'Должность EN' }}</label>
            <input class="form-control" name="position[en]" type="text" id="position_en" value="{{ isset($translatedData['position']->en) ? $translatedData['position']->en : ''}}" >
            {!! $errors->first('position[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="position[kz]" class="control-label">{{ 'Должность KZ' }}</label>
            <input class="form-control" name="position[kz]" type="text" id="position_kz" value="{{ isset($translatedData['position']->kz) ? $translatedData['position']->kz : ''}}" >
            {!! $errors->first('position[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>


    <div class="tab-pane active in ru-content" id="custom-tabs-one-ru" role="tabpanel" aria-labelledby="custom-tabs-one-ru-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="address[ru]" class="control-label">{{ 'Адрес RU' }}</label>
            <input class="form-control" name="address[ru]" type="text" id="address_ru" value="{{ isset($translatedData['address']->ru) ? $translatedData['address']->ru : ''}}" >
            {!! $errors->first('address[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="address[en]" class="control-label">{{ 'Адрес EN' }}</label>
            <input class="form-control" name="address[en]" type="text" id="address_en" value="{{ isset($translatedData['address']->en) ? $translatedData['address']->en : ''}}" >
            {!! $errors->first('address[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
            <label for="address[kz]" class="control-label">{{ 'Адрес KZ' }}</label>
            <input class="form-control" name="address[kz]" type="text" id="address_kz" value="{{ isset($translatedData['address']->kz) ? $translatedData['address']->kz : ''}}" >
            {!! $errors->first('address[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>


<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="email" class="control-label">{{ 'Электронная почта' }}</label>
    <input class="form-control" name="email" type="text" id="email" value="{{ isset($supervisor->email) ? $supervisor->email : ''}}" >
    {!! $errors->first('email"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="phone" class="control-label">{{ 'Номер телефона' }}</label>
    <input class="form-control" name="phone" type="text" id="phone" value="{{ isset($supervisor->phone) ? $supervisor->phone : ''}}" >
    {!! $errors->first('phone"', '<p class="help-block">:message</p>') !!}
</div>



<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
    <label for="image" class="control-label">{{ 'Фото' }}</label>
    <input class="form-control" name="image" type="file" id="image" value="{{ isset($supervisor->image) ? $supervisor->image : ''}}" >
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
@if (isset($supervisor->image))
    <div class="form-group">
        <img src="{{ \Config::get('constants.alias.cdn_url').$supervisor->image }}" alt="" width="300px;">
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