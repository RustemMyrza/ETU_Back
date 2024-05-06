<div id="inputs-container">
    @if(isset($slider))
        @foreach(json_decode($slider->images) as $key => $item)
            <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
                <label for="image_{{ $key }}" class="control-label">{{ 'Изображение ' . intval($key + 1) }}</label>
                <input class="form-control" name="image_{{ $key }}" type="file" id="image_{{ $key }}" value="{{ url($item) }}" onchange="addInput(this)">
                {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="form-group">
                <img src="{{ url($item) }}" alt="" width="200px;">
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