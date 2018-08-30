<div class="panel-body pb5 pt20">
    @if($errors->user_address)
        @foreach($errors->user_address->all() as $error)
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-remove pr10"></i>
                {{ $error }}
            </div>
        @endforeach
    @endif

    @if (\Session::has('success_user_address'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-check pr10"></i>
            {{ \Session::get('success_user_address') }}
        </div>
    @endif
    <div class="form-group row">
        <label class="col-xs-12">Адреса реєстрації</label>
    </div>

    <div class="form-group row">
        <label for="registration_district" class="col-lg-3 control-label">Область</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->registrationAddress ? $user->registrationAddress->district : '' }} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="registration_city" class="col-lg-3 control-label">Населений пункт</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->registrationAddress ? $user->registrationAddress->city : '' }} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="registration_street" class="col-lg-3 control-label">Вулиця</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->registrationAddress ? $user->registrationAddress->street : '' }} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="registration_building" class="col-lg-3 control-label">Будинок</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->registrationAddress ? $user->registrationAddress->building : '' }} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="registration_apartment" class="col-lg-3 control-label">Помешкання</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->registrationAddress ? $user->registrationAddress->apartment : '' }} </p>
        </div>
    </div>
</div>
<div class="panel-body pb5 pt20">
    <div class="form-group row">
        <label class="col-xs-12">Адреса проживання</label>
    </div>

    <div class="form-group row">
        <label for="living_district" class="col-lg-3 control-label">Область</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->livingAddress ? $user->livingAddress->district : ''}} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="living_city" class="col-lg-3 control-label">Населений пункт</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->livingAddress ? $user->livingAddress->city : ''}} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="living_street" class="col-lg-3 control-label">Вулиця</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->livingAddress ? $user->livingAddress->street : ''}} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="living_building" class="col-lg-3 control-label">Будинок</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->livingAddress ? $user->livingAddress->building : ''}} </p>
        </div>
    </div>
    <div class="form-group row">
        <label for="living_apartment" class="col-lg-3 control-label">Помешкання</label>
        <div class="col-lg-8">
            <p class="form-control custom-field">{{ $user->livingAddress ? $user->livingAddress->apartment : ''}} </p>
        </div>
    </div>

</div>
