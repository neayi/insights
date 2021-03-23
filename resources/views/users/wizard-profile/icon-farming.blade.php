@php
$state = '';
if(in_array($uuid, old('farming_type', []))){
    $state = 'checked';
}
@endphp

<input {{$state}} id="c-{{$uuid}}" type="checkbox" name="farming_type[]" value="{{$uuid}}"/>
<label for="c-{{$uuid}}">
    <img src="{{asset('storage/'.str_replace('public/', '', $icon))}}" class="rounded-circle mb-2"/>
    <span class="d-block">{{$label}}</span>
</label>
