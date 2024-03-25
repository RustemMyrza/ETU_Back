@extends('adminlte::page')

@section('title', 'Контакты')

@section('content_header')
    <h1>Контакты</h1>
@stop
@section('content')
    <div class="card-body">
        @include('flash-message')
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ url('/admin/contacts') }}" accept-charset="UTF-8" class="form-horizontal"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <!-- <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-ru-tab" data-toggle="pill" href="#custom-tabs-one-ru"
                       role="tab"
                       aria-controls="custom-tabs-one-ru" aria-selected="true">Русский</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-en-tab" data-toggle="pill" href="#custom-tabs-one-en"
                       role="tab"
                       aria-controls="custom-tabs-one-en" aria-selected="false">Английский</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-kz-tab" data-toggle="pill" href="#custom-tabs-one-kz"
                       role="tab"
                       aria-controls="custom-tabs-one-kz" aria-selected="false">Казахский</a>
                </li>
            </ul> -->

            <div class="tab-content col-md-12" id="custom-tabs-one-tabContent">
                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Адрес' }}</label>
                    <input class="form-control" name="address" type="text" id="address"
                            value="{{ isset($contacts->address) ? $contacts->address : ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Номер приемной комиссии №1' }}</label>
                    <input class="form-control" name="admissions_committee_num_1" type="text" id="admissions_committee_num_1"
                            value="{{ isset($contacts->admissions_committee_num_1) ? $contacts->admissions_committee_num_1: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Номер приемной комиссии №2' }}</label>
                    <input class="form-control" name="admissions_committee_num_2" type="text" id="admissions_committee_num_2"
                            value="{{ isset($contacts->admissions_committee_num_2) ? $contacts->admissions_committee_num_2: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Почта приемной комиссии' }}</label>
                    <input class="form-control" name="admissions_committee_mail" type="text" id="admissions_committee_mail"
                            value="{{ isset($contacts->admissions_committee_mail) ? $contacts->admissions_committee_mail: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Номер приемной ректора' }}</label>
                    <input class="form-control" name="rectors_reception_num" type="text" id="rectors_reception_num"
                            value="{{ isset($contacts->rectors_reception_num) ? $contacts->rectors_reception_num: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Номер офиса регистратора' }}</label>
                    <input class="form-control" name="office_receptionist_num" type="text" id="office_receptionist_num"
                            value="{{ isset($contacts->office_receptionist_num) ? $contacts->office_receptionist_num: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Название аккаунта Tiktok' }}</label>
                    <input class="form-control" name="tiktok_name" type="text" id="tiktok_name"
                            value="{{ isset($contacts->tiktok_name) ? $contacts->tiktok_name: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Ссылка аккаунта Tiktok' }}</label>
                    <input class="form-control" name="tiktok_link" type="text" id="tiktok_link"
                            value="{{ isset($contacts->tiktok_link) ? $contacts->tiktok_link: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Название аккаунта Instagram' }}</label>
                    <input class="form-control" name="instagram_name" type="text" id="instagram_name"
                            value="{{ isset($contacts->instagram_name) ? $contacts->instagram_name: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Ссылка аккаунта Instagram' }}</label>
                    <input class="form-control" name="instagram_link" type="text" id="instagram_link"
                            value="{{ isset($contacts->instagram_link) ? $contacts->instagram_link: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Ссылка аккаунта Facebook' }}</label>
                    <input class="form-control" name="facebook_link" type="text" id="facebook_link"
                            value="{{ isset($contacts->facebook_link) ? $contacts->facebook_link: ''}}">
                </div>

                <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                    <label for="director_name" class="control-label">{{ 'Ссылка аккаунта YouTube' }}</label>
                    <input class="form-control" name="youtube_link" type="text" id="youtube_link"
                            value="{{ isset($contacts->youtube_link) ? $contacts->youtube_link: ''}}">
                </div>
            </div>

            <!--
            <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                <label for="phone_number" class="control-label">{{ 'Номер телефона' }}</label>
                <input class="form-control" name="phone_number" type="text" id="phone_number"
                       value="{{ isset($contacts->phone_number) ? $contacts->phone_number : ''}}">
            </div>

            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                <label for="email" class="control-label">{{ 'Почта' }}</label>
                <input class="form-control" name="email" type="email" id="email"
                       value="{{ isset($contacts->email) ? $contacts->email : ''}}">
            </div>

            <div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
                <label for="address" class="control-label">{{ 'Адрес1' }}</label>
                <input class="form-control" name="address" type="text" id="address"
                       value="{{ isset($contacts->address) ? $contacts->address : ''}}">
            </div>

            <div class="form-group {{ $errors->has('address2') ? 'has-error' : ''}}">
                <label for="address2" class="control-label">{{ 'Адрес2' }}</label>
                <input class="form-control" name="address2" type="text" id="address2"
                       value="{{ isset($contacts->address2) ? $contacts->address2 : ''}}">
            </div>

            <div class="form-group {{ $errors->has('whats_app') ? 'has-error' : ''}}">
                <label for="whats_app" class="control-label">{{ 'Whats_app' }}</label>
                <input class="form-control" name="whats_app" type="text" id="whats_app"
                       value="{{ isset($contacts->whats_app) ? $contacts->whats_app : ''}}">
            </div>

            <div class="form-group {{ $errors->has('telegram') ? 'has-error' : ''}}">
                <label for="telegram" class="control-label">{{ 'Тelegram' }}</label>
                <input class="form-control" name="telegram" type="text" id="telegram"
                       value="{{ isset($contacts->telegram) ? $contacts->telegram : ''}}">
            </div>

            <div class="form-group {{ $errors->has('wechat') ? 'has-error' : ''}}">
                <label for="facebook" class="control-label">{{ 'Wechat' }}</label>
                <input class="form-control" name="facebook" type="text" id="facebook"
                       value="{{ isset($contacts->facebook) ? $contacts->facebook : ''}}">
            </div>

            <div class="form-group {{ $errors->has('insta') ? 'has-error' : ''}}">
                <label for="insta" class="control-label">{{ 'instagram' }}</label>
                <input class="form-control" name="insta" type="text" id="insta"
                       value="{{ isset($contacts->insta) ? $contacts->insta : ''}}">
            </div>

            <div class="form-group {{ $errors->has('link') ? 'has-error' : ''}}">
                <label for="link" class="control-label">{{ 'Карта' }}</label>
                <input class="form-control" name="link" type="text" id="link"
                       value="{{ isset($contacts->link) ? $contacts->link : ''}}">
            </div>
            -->

            <!-- <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                <label for="description" class="control-label">{{ 'Описание' }}</label>
                <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ isset($contacts->description) ? $contacts->description : ''}}</textarea>
            </div> -->

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="{{ 'Сохранить' }}">
            </div>


        </form>
@endsection
