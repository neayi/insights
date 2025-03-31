@php
    $state = 'required';
    $country = $context['country'] ?? old('country');
    $postalCode = $context['postal_code'] ?? old('postal_code');
    if($country && $postalCode && !$errors->has('country') && !$errors->has('postal_code')){
        $state = 'success';
    }
    if($country > '' && $postalCode > '') {
        $state = 'success';
    }
@endphp

<div class="form-group">
    <label id="label-fill-geolocation" class="label-big {{ $state }}">
        @lang('wiki_profile.fill_location_header')
        <i id="fill-geolocation-spinner" class="fas fa-spinner fa-spin" style="font-size: 20px;vertical-align: top; display:none"></i>
    </label>
    <p class="h6 font-italic mb-3"><small>@lang('wiki_profile.fill_location_hint')</small></p>
    <div class="row align-items-center">
        <div class="col-3">
            <label>@lang('wiki_profile.fill_country')</label>
        </div>
        <div class="col-md-7 col-9">
            <select value="{{ $country }}" id="input-country" name="country"
                    autocomplete="country" class="form-control" placeholder="" required>
                @foreach(Countries::getList() as $countryCode => $countryName)
                    {{-- pré-sélection du pays en se basant sur la langue connue (peu fiable mais fait le job pour FR) --}}
                    <option value="{{$countryCode}}" @if($countryCode === strtoupper(app()->getLocale())) selected @endif>
                        {{$countryName}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-2 align-items-center">
        <div class="col-3">
            <label>@lang('wiki_profile.fill_postal_code')</label>
        </div>
        <div class="col-9 col-md-3">
            <input value="{{$postalCode}}" type="text" id="input-postal-code" name="postal_code" autocomplete="postal-code" class="form-control" placeholder="" required>
            <input value="@if ('success' === $state) success @endif" type="text" style="height: 1px; opacity: 0; display: block" id="input-check-geolocation" title="Failed geolocation" name="check_geolocation" required>
        </div>
    </div>
</div>
