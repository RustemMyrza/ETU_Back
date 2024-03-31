<div class="form-group {{ $errors->has('position') ? 'has-error' : ''}}">
    <label for="position" class="control-label">{{ 'Позиция' }}</label>
    <input class="form-control" name="position" type="text" id="position" value="{{ isset($vacancy->position) ? $vacancy->position : ''}}" >
    {!! $errors->first('position"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">{{ 'Описание' }}</label>
    <input class="form-control" name="description" type="text" id="description" value="{{ isset($vacancy->description) ? $vacancy->description : ''}}" >
    {!! $errors->first('description"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('experience') ? 'has-error' : ''}}">
    <label for="experience" class="control-label">{{ 'Опыт работы' }}</label>
    <input class="form-control" name="experience" type="text" id="experience" value="{{ isset($vacancy->experience) ? $vacancy->experience : ''}}" >
    {!! $errors->first('experience"', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group {{ $errors->has('date') ? 'has-error' : ''}}">
    <label for="date" class="control-label">{{ 'Дата' }}</label>
    <input class="form-control" name="date" type="date" id="date" value="{{ isset($vacancy->date) ? $vacancy->date : ''}}" >
    {!! $errors->first('date"', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Обновить' : 'Создать' }}">
</div>