@php
$state = '';
if(in_array($uuid, old('farming_type', [])) || (isset($checked) && $checked == true)){
    $state = 'checked';
}
@endphp

<input {{$state}} id="c-{{$uuid}}" type="checkbox" name="farming_type[]" value="{{$uuid}}"/>
<label for="c-{{$uuid}}">
    @if(isset($icon))
        <img src="{{asset('storage/'.str_replace('public/', '', $icon))}}" class="rounded-circle mb-2"/>
    @else
        <img src="{{asset('images/phblanc.png')}}" class="rounded-circle mb-2"/>
    @endif
    <span class="d-block">{{$label}}</span>
</label>
