<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
    <label for="image" class="control-label">{{ 'Изображение' }}</label>
    <input class="form-control" name="image" type="file" id="image" value="{{ isset($instagramImage->image) ? $instagramImage->image : ''}}" required>
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
@if (isset($instagramImage->image))
    <div class="form-group">
        <img src="{{ url($instagramImage->image) }}" alt="{{ url($instagramImage->image) }}" width="300px;">
    </div>
@endif


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Обновить' : 'Создать' }}">
</div>