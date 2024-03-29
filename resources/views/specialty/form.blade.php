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
    <div class="tab-pane active in" id="custom-tabs-one-ru" role="tabpanel" aria-labelledby="custom-tabs-one-ru-tab">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            <label for="name_ru" class="control-label">{{ 'Название RU' }}</label>
            <input class="form-control" name="name[ru]" type="text" id="name_ru" value="{{ isset($translatedName->ru) ? $translatedName->ru : old('name.ru')}}" >
            {!! $errors->first('name[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            <label for="name_en" class="control-label">{{ 'Название EN' }}</label>
            <input class="form-control" name="name[en]" type="text" id="name_en" value="{{ isset($translatedName->en) ? $translatedName->en : old('name.en')}}" >
            {!! $errors->first('name[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            <label for="name_kz" class="control-label">{{ 'Название KZ' }}</label>
            <input class="form-control" name="name[kz]" type="text" id="name_kz" value="{{ isset($translatedName->kz) ? $translatedName->kz : old('name.kz')}}" >
            {!! $errors->first('name[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="code" class="control-label">{{ 'Код специальности' }}</label>
    <input class="form-control" name="code" type="text" id="code" value="{{ isset($specialty->code) ? $specialty->code : old('code')}}" >
    {!! $errors->first('code"', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Обновить' : 'Создать' }}">
</div>
