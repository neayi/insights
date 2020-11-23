<div class="row">
    <div class="col-12">
        <select name="role" id="input-role" class="selectpicker" title="-">
            @foreach($userRoles as $role)
                <option value="{{$role['role']}}">@lang('wiki_profile.'.$role['role'])</option>
            @endforeach
        </select>
    </div>
</div>

