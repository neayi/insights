<div class="form-group">
    <select class="form-control" name="user_role">
        @foreach($userRoles as $role)
            <option value="{{$role['role']}}">@lang('wiki_profile.'.$role['role'])</option>
        @endforeach
    </select>
</div>
