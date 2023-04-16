@php
    $state = 'required';
    if(old('role')){
        $state = 'success';
    }
@endphp
<div class="form-group">
    <label id="state-role" class="label-big {{$state}} mb-3">@lang('wiki_profile.fill_role_header')</label>
    <div class="row">
        <div class="col-12">
            <select name="role" id="input-role" class="selectpicker" title="-">
                @foreach($userRoles as $role)
                    <option @if($role['role'] === old('role')) selected @endif value="{{$role['role']}}">
                        @lang('wiki_profile.'.$role['role'])
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <small class="form-text text-muted font-weight-semibold mt-2">
        @lang('wiki_profile.fill_role_hint')
    </small>
</div>
