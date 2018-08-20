<form class="form-horizontal" method="post" action="{{route('admin.db.users.update.address', $user->id)}}" role="form">
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
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="col-xs-12">Адреса реєстрації</label>
        </div>

        <div class="form-group">
            <label for="registration_district" class="col-lg-3 control-label">Область</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="registrationAddress[district]" value="{{ $user->registrationAddress ? $user->registrationAddress->district : '' }}">
            </div>
        </div>
        <div class="form-group">
            <label for="registration_city" class="col-lg-3 control-label">Населений пункт</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="registrationAddress[city]" value="{{ $user->registrationAddress ? $user->registrationAddress->city : '' }}">
            </div>
        </div>
        <div class="form-group">
            <label for="registration_street" class="col-lg-3 control-label">Вулиця</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="registrationAddress[street]" value="{{ $user->registrationAddress ? $user->registrationAddress->street : '' }}">
            </div>
        </div>
        <div class="form-group">
            <label for="registration_building" class="col-lg-3 control-label">Будинок</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="registrationAddress[building]" value="{{ $user->registrationAddress ? $user->registrationAddress->building : '' }}">
            </div>
        </div>
        <div class="form-group">
            <label for="registration_apartment" class="col-lg-3 control-label">Помешкання</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="registrationAddress[apartment]" value="{{ $user->registrationAddress ? $user->registrationAddress->apartment : '' }}">
            </div>
        </div>
    </div>
    <div class="panel-body pb5 pt20">
        <div class="form-group">
            <label class="col-xs-12">Адреса проживання</label>
        </div>

        <div class="form-group">
            <label for="living_district" class="col-lg-3 control-label">Область</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="livingAddress[district]"  value="{{ $user->livingAddress ? $user->livingAddress->district : ''}}">
            </div>
        </div>
        <div class="form-group">
            <label for="living_city" class="col-lg-3 control-label">Населений пункт</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="livingAddress[city]"  value="{{ $user->livingAddress ? $user->livingAddress->city : ''}}">
            </div>
        </div>
        <div class="form-group">
            <label for="living_street" class="col-lg-3 control-label">Вулиця</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="livingAddress[street]"  value="{{ $user->livingAddress ? $user->livingAddress->street : ''}}">
            </div>
        </div>
        <div class="form-group">
            <label for="living_building" class="col-lg-3 control-label">Будинок</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="livingAddress[building]"  value="{{ $user->livingAddress ? $user->livingAddress->building : ''}}">
            </div>
        </div>
        <div class="form-group">
            <label for="living_apartment" class="col-lg-3 control-label">Помешкання</label>
            <div class="col-lg-8">
                <input class="form-control custom-field" name="livingAddress[apartment]"  value="{{ $user->livingAddress ? $user->livingAddress->apartment : ''}}">
            </div>
        </div>

    </div>
    <div class="panel-footer text-right">
        <button type="submit" class="btn btn-info ph25">Зберегти</button>
    </div>
</form>
