@php
    $state = 'required';
    if(old('firstname') && !$errors->has('postal_code')){
        $state = 'success';
    }
@endphp
<div class="form-group">
    <label id="state-postal" class="label-big {{$state}} mb-3">@lang('wiki_profile.fill_postal_code_header')</label>
    <div class="row align-items-center">
        <div class="col-lg-5 col-3">
            <label>@lang('wiki_profile.fill_postal_code')</label>
        </div>
        <div class="col-lg-5 col-9">
            <input value="{{old('postal_code')}}" type="text" id="input-postal" name="postal_code"
                   autocomplete="postal-code" class="form-control" placeholder="">
        </div>
    </div>
    <small class="form-text text-muted font-weight-semibold mt-2">
        @lang('wiki_profile.fill_postal_code_hint')
        <p style="cursor:pointer;" id="no-postal-code">Cliquez ici si vous n'avez pas de code postal.</p>
        <input type="hidden" value="0" name="no_postal_code" id="no_postal_code_input"/>
        <p style="cursor:pointer; display: none;" id="fill-postal-code">Je souhaite renseigner mon code postal</p>
    </small>
</div>
