@php
$state = '';
if(in_array($uuid, old('farming_type', [])) || (isset($checked) && $checked == true)){
    $state = 'checked';
}
@endphp

<input {{$state}} id="c-{{$uuid}}" type="checkbox" name="farming_type[]" value="{{$uuid}}"/>
<label for="c-{{$uuid}}">
    @if(isset($icon))
        <span class="rounded-circle mb-2">
            @include('glyph.glyph', ['glyph' => $icon, 'type' => 'farming'])
        </span>
    @else
        <img src="{{asset('images/phblanc.png')}}" class="rounded-circle mb-2"/>
    @endif
    <span class="d-block">{{$label}}</span>
</label>
