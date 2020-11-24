@php
$state = '';
if(in_array($uuid, old('farming_type', []))){
    $state = 'checked';
}
@endphp

<input {{$state}} id="c-{{$uuid}}" type="checkbox" name="farming_type[]" value="{{$uuid}}"/>
<label for="c-{{$uuid}}">
    <img src="{{asset('images/'.$code.'.svg')}}" class="rounded-circle mb-2" />
    <span class="d-block">@lang('wiki_profile.farming_'.$code)</span>
</label>
