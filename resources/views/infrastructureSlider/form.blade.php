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
        <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
            <label for="title[ru]" class="control-label">{{ 'Заголовок RU' }}</label>
            <input class="form-control" name="title[ru]" type="text" id="title_ru" value="{{ isset($infrastructureSlider->getTitle->ru) ? $infrastructureSlider->getTitle->ru : ''}}" >
            {!! $errors->first('title[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
            <label for="title[en]" class="control-label">{{ 'Заголовок EN' }}</label>
            <input class="form-control" name="title[en]" type="text" id="title_en" value="{{ isset($infrastructureSlider->getTitle->en) ? $infrastructureSlider->getTitle->en : ''}}" >
            {!! $errors->first('title[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
            <label for="title[kz]" class="control-label">{{ 'Заголовок KZ' }}</label>
            <input class="form-control" name="title[kz]" type="text" id="title_kz" value="{{ isset($infrastructureSlider->getTitle->kz) ? $infrastructureSlider->getTitle->kz : ''}}" >
            {!! $errors->first('title[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div id="inputs-container">
    @if(isset($infrastructureSlider))
        @foreach(json_decode($infrastructureSlider->images) as $key => $item)
            <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
                <label for="image_{{ $key }}" class="control-label">{{ 'Изображение ' . intval($key + 1) }}</label>
                <input class="form-control" name="image_{{ $key }}" type="file" id="image_{{ $key }}" value="{{ url($item) }}" onchange="addInput(this)">
                {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group">
                <img src="{{ url($item) }}" alt="{{ url($item) }}" width="200px;">
            </div>
        @endforeach

        @else
            <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
                <label for="image_1" class="control-label">{{ 'Изображение 1' }}</label>
                <input class="form-control" name="image_1" type="file" id="image_1" onchange="addInput(this)">
                {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
            </div>
    @endif
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

<script>
    const inputElements = document.querySelectorAll('#inputs-container input[type="file"]');

    let inputCounter = inputElements.length; // Начинаем счетчик с 2, так как у нас уже есть input с id="image_1"

    function addInput(input) {
    const container = document.getElementById('inputs-container');

    if (input.files.length > 0 && inputCounter <= 10) {
        // Создаем новый div элемент
        const newDiv = document.createElement('div');
        newDiv.className = 'form-group';

        // Создаем label элемент
        const newLabel = document.createElement('label');
        newLabel.setAttribute('for', `image_${inputCounter + 1}`);
        newLabel.className = 'control-label';
        newLabel.textContent = `Изображение ${inputCounter + 1}`;

        // Создаем input элемент
        const newInput = document.createElement('input');
        newInput.className = 'form-control';
        newInput.type = 'file';
        newInput.name = `image_${inputCounter + 1}`;
        newInput.id = `image_${inputCounter + 1}`;
        newInput.setAttribute('onchange', 'addInput(this)');

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
        inputCounter++;
    } else {
        alert('Максимальное количество файлов достигнуто.');
    }
}

</script>