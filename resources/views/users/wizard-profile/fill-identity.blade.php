@php
    $state = 'required';
    if(old('firstname') && !$errors->has('firstname') && !$errors->has('lastname')){
        $state = 'success';
    }
    if($firstname !== "" && $lastname !== ""){
        $state = 'success';
    }
@endphp

<div class="form-group">
    <label id="state-identity" class="label-big {{$state}} mb-3">@lang('wiki_profile.fill_identity_header')</label>
    <div class="form-row">
        <div class="col-md-5">
            <input type="text" name="firstname" autocomplete="given-name" value="{{old('firstname', $firstname)}}"
                   class="input-identity form-control input-big" id="firstname" aria-describedby="" placeholder="@lang('wiki_profile.fill_identity_firstname')">
        </div>
        <div class="col-md-7">
            <input type="text" name="lastname" autocomplete="family-name" value="{{old('lastname', $lastname)}}"
                   class="input-identity form-control input-big" id="surname" aria-describedby="" placeholder="@lang('wiki_profile.fill_identity_lastname')">
        </div>
    </div>
    <small class="form-text text-muted font-weight-semibold mt-2">
        @lang('wiki_profile.fill_identity_hint')
    </small>
</div>
