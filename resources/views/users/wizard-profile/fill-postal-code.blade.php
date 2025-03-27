@php
    $state = 'required';
    if(old('firstname') && !$errors->has('postal_code')){
        $state = 'success';
    }
@endphp
<div class="form-group">
    <label id="state-postal" class="label-big {{$state}} mb-3">@lang('wiki_profile.fill_location_header')</label>
    <div class="row align-items-center">
        <div class="col-lg-5 col-3">
            <label>@lang('wiki_profile.fill_postal_code')</label>
        </div>
        <div class="col-lg-5 col-9">
            <input value="{{old('postal_code')}}" type="text" id="input-postal" name="postal_code"
                   autocomplete="postal-code" class="form-control" placeholder="">
        </div>
    </div>
</div>
