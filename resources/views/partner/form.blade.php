<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ 'Название' }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($partner->name) ? $partner->name : ''}}" >
    {!! $errors->first('name"', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
    <label for="image" class="control-label">{{ 'Изображение' }}</label>
    <input class="form-control" name="image" type="file" id="image" value="{{ isset($partner->image) ? $partner->image : ''}}" >
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
@if (isset($partner->image))
    <div class="form-group">
        <img src="{{ \Config::get('constants.alias.cdn_url').$partner->image }}" alt="" width="300px;">
    </div>
@endif

<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="type" class="control-label">{{ 'Тип партнера' }}</label>
    <br>
    <select name="type" id="type">
        <option value="1" {{ isset($partner->type) ? $partner->type == 1 ? 'selected' : '' : '' }} >Партнеры</option>
        <option value="2" {{ isset($partner->type) ? $partner->type == 2 ? 'selected' : '' : '' }} >Международные партнеры</option>
    </select>
    {!! $errors->first('type"', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Обновить' : 'Создать' }}">
</div>
<script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
<script>
     document.querySelectorAll('.ckeditor_textarea').forEach(function(element) {
        CKEDITOR.replace(element);
    });
</script>