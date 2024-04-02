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
        <div class="form-group {{ $errors->has('category') ? 'has-error' : ''}}">
            <label for="category[ru]" class="control-label">{{ 'Категория RU' }}</label>
            <input class="form-control" name="category[ru]" type="text" id="category_ru" value="{{ isset($translatedData['category']->ru) ? $translatedData['category']->ru : ''}}" >
            {!! $errors->first('category[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('category') ? 'has-error' : ''}}">
            <label for="category[en]" class="control-label">{{ 'Категория EN' }}</label>
            <input class="form-control" name="category[en]" type="text" id="category_en" value="{{ isset($translatedData['category']->en) ? $translatedData['category']->en : ''}}" >
            {!! $errors->first('category[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('category') ? 'has-error' : ''}}">
            <label for="category[kz]" class="control-label">{{ 'Категория KZ' }}</label>
            <input class="form-control" name="category[kz]" type="text" id="category_kz" value="{{ isset($translatedData['category']->kz) ? $translatedData['category']->kz : ''}}" >
            {!! $errors->first('category[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>




<div class="tab-content col-md-12" id="custom-tabs-one-tabContent">
    <div class="tab-pane active in ru-content" id="custom-tabs-one-ru" role="tabpanel" aria-labelledby="custom-tabs-one-ru-tab">
        <div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
            <label for="note[ru]" class="control-label">{{ 'Примечание RU' }}</label>
            <input class="form-control" name="note[ru]" type="text" id="note_ru" value="{{ isset($translatedData['note']->ru) ? $translatedData['note']->ru : ''}}" >
            {!! $errors->first('category[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
            <label for="note[en]" class="control-label">{{ 'Примечание EN' }}</label>
            <input class="form-control" name="note[en]" type="text" id="note_en" value="{{ isset($translatedData['note']->en) ? $translatedData['note']->en : ''}}" >
            {!! $errors->first('note[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
            <label for="note[kz]" class="control-label">{{ 'Примечание KZ' }}</label>
            <input class="form-control" name="note[kz]" type="text" id="note_kz" value="{{ isset($translatedData['note']->kz) ? $translatedData['note']->kz : ''}}" >
            {!! $errors->first('note[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
    <label for="amount" class="control-label">{{ 'Размер скидки %' }}</label>
    <input class="form-control" name="amount" type="number" id="amount" value="{{ isset($honorsStudentDiscount->amount) ? $honorsStudentDiscount->amount : ''}}" >
    {!! $errors->first('amount"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('gpa') ? 'has-error' : ''}}">
    <label for="gpa" class="control-label">{{ 'GPA' }}</label>
    <input class="form-control" name="gpa" type="text" id="gpa" value="{{ isset($honorsStudentDiscount->gpa) ? $honorsStudentDiscount->gpa : ''}}" >
    {!! $errors->first('gpa"', '<p class="help-block">:message</p>') !!}
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