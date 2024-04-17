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
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            <label for="name[ru]" class="control-label">{{ 'Название RU' }}</label>
            <input class="form-control" name="name[ru]" type="text" id="name_ru" value="{{ isset($translatedName->ru) ? $translatedName->ru : ''}}" >
            {!! $errors->first('name[ru]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade en-content" id="custom-tabs-one-en" role="tabpanel" aria-labelledby="custom-tabs-one-en-tab">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            <label for="name[en]" class="control-label">{{ 'Название EN' }}</label>
            <input class="form-control" name="name[en]" type="text" id="name_en" value="{{ isset($translatedName->en) ? $translatedName->en : ''}}" >
            {!! $errors->first('name[en]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="tab-pane fade kz-content" id="custom-tabs-one-kz" role="tabpanel" aria-labelledby="custom-tabs-one-kz-tab">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            <label for="name[kz]" class="control-label">{{ 'Название KZ' }}</label>
            <input class="form-control" name="name[kz]" type="text" id="name_kz" value="{{ isset($translatedName->kz) ? $translatedName->kz : ''}}" >
            {!! $errors->first('name[kz]"', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>


<div class="form-group {{ $errors->has('document') ? 'has-error' : ''}}">
    <label for="document" class="control-label">{{ 'Документ' }}</label>
    <input class="form-control" name="document" type="file" id="document" value="{{ isset($document->link) ? $document->link : ''}}" >
    {!! $errors->first('document', '<p class="help-block">:message</p>') !!}
</div>
@if (isset($document->link))
    <div class="form-group">
    <a href="{{ url($document->link) }}">{{$document->name ? Str::limit($translatedName->ru, 50) : ''}}</a>
    </div>
@endif


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Обновить' : 'Создать' }}">
</div>