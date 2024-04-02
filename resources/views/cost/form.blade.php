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
        <div class="form-group {{ $errors->has('program') ? 'has-error' : ''}}">
            <label for="program[ru]" class="control-label">{{ 'Группа образовательных программ RU' }}</label>
            <input class="form-control" name="program[ru]" type="text" id="program_ru" value="{{ isset($translatedData['program']->ru) ? $translatedData['program']->ru : ''}}" >
            {!! $errors->first('program[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('program') ? 'has-error' : ''}}">
            <label for="program[en]" class="control-label">{{ 'Группа образовательных программ EN' }}</label>
            <input class="form-control" name="program[en]" type="text" id="program_en" value="{{ isset($translatedData['program']->en) ? $translatedData['program']->en : ''}}" >
            {!! $errors->first('program[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('program') ? 'has-error' : ''}}">
            <label for="v[kz]" class="control-label">{{ 'Группа образовательных программ KZ' }}</label>
            <input class="form-control" name="program[kz]" type="text" id="program_kz" value="{{ isset($translatedData['program']->kz) ? $translatedData['program']->kz : ''}}" >
            {!! $errors->first('program[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('student_type') ? 'has-error' : ''}}">
    <label for="type" class="control-label">{{ 'Форма обучения' }}</label>
    <br>
    <select name="type" id="type">
        <option value="1" {{ isset($cost->type) ? $cost->type == 1 ? 'selected' : '' : '' }} >На очное отделение Бакалавриата</option>
        <option value="2" {{ isset($cost->type) ? $cost->type == 2 ? 'selected' : '' : '' }} >На сокращенную форму Бакалавриата</option>
    </select>
    {!! $errors->first('student_type"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('first') ? 'has-error' : ''}}" id="first_year_block">
    <label for="first_year" class="control-label">{{ '1 курс' }}</label>
    <input class="form-control" name="first_year" type="number" id="first_year" value="{{ isset($cost->first) ? $cost->first : ''}}" >
    {!! $errors->first('first_year"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('second') ? 'has-error' : ''}}">
    <label for="second_year" class="control-label">{{ '2 курс' }}</label>
    <input class="form-control" name="second_year" type="number" id="second_year" value="{{ isset($cost->second) ? $cost->second : ''}}" >
    {!! $errors->first('second_year"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('third') ? 'has-error' : ''}}">
    <label for="third_year" class="control-label">{{ '3 курс' }}</label>
    <input class="form-control" name="third_year" type="number" id="third_year" value="{{ isset($cost->third) ? $cost->third : ''}}" >
    {!! $errors->first('third_year"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('fourth') ? 'has-error' : ''}}">
    <label for="fourth_year" class="control-label">{{ '4 курс' }}</label>
    <input class="form-control" name="fourth_year" type="number" id="fourth_year" value="{{ isset($cost->fourth) ? $cost->fourth : ''}}" >
    {!! $errors->first('fourth_year"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('total') ? 'has-error' : ''}}">
    <label for="total" class="control-label">{{ 'Общая стоимость' }}</label>
    <input class="form-control" name="total" type="number" id="total" value="{{ isset($cost->total) ? $cost->total : ''}}" >
    {!! $errors->first('total"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Обновить' : 'Создать' }}">
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Находим ссылки для изменения вкладок
    var ruLink = document.getElementById("custom-tabs-one-ru-tab");
    var enLink = document.getElementById("custom-tabs-one-en-tab");
    var kzLink = document.getElementById("custom-tabs-one-kz-tab");
    var type = document.getElementById("type");
    var first_year = document.getElementById("first_year_block");

    type.addEventListener('change', function() {
        var value = this.value

        if (value == 2)
        {
            first_year.style.display = 'none';
        }

        else if (value == 1)
        {
            first_year.style.display = 'block';
        }
    })

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