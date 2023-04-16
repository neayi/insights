@php
    $state = 'required';
    if(old('email') && !$errors->has('email')){
        $state = 'success';
    }
    if($email !== ""){
        $state = 'success';
    }
@endphp
<div class="form-group">
    <label id="state-email" class="label-big {{$state}} mb-3">@lang('wiki_profile.fill_email_header')</label>
    <div class="form-row">
        <div class="col-md-{{ isset($width) ? $width : 5 }}">
            <input type="text" name="email" value="{{old('email', $email)}}" class="form-control input-big"
                   id="input-email" autocomplete="nope" aria-describedby="" placeholder="@lang('wiki_profile.fill_email')">
            <small class="form-text text-muted font-weight-semibold mt-2">
                @lang('wiki_profile.fill_email_hint')
            </small>
        </div>
    </div>
</div>
