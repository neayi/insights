@php
    $state = 'required';
    if(old('firstname') && !$errors->has('postal_code')){
        $state = 'success';
    }
@endphp
<div class="form-group">
    <label id="state-postal" class="label-big {{$state}} mb-3">J'habite</label>
    <div class="row align-items-center">
        <div class="col-lg-7 col-3">
            <label>Code postal</label>
        </div>
        <div class="col-lg-5 col-9">
            <input value="{{old('postal_code')}}" type="text" id="input-postal" name="postal_code" class="form-control" placeholder="">
        </div>
    </div>
    <small class="form-text text-muted font-weight-semibold mt-2">
        Parce que nos pratiques ne sont pas partout les mêmes.
    </small>
</div>
