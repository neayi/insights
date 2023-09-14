<div class="modal fade" id="headerEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-edit mx-0 mx-sm-auto" role="document">
        <div class="modal-content p-md-3 p-1">
            <button type="button" class="close text-right d-block" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="material-icons">close</span>
            </button>
            <div class="modal-body pt-2">
                <div class="container-fluid">
                    <form id="form-update-main-data" action="{{ route('context.update') }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-big success mb-3">@lang('wiki_profile.fill_role_header')</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <select name="role" id="input-role" title="-" class="selectpicker w-100">
                                                @foreach($userRoles as $roleAllowed)
                                                    <option @if($roleAllowed['role'] === $role) selected @endif value="{{$roleAllowed['role']}}">
                                                        @lang('wiki_profile.'.$roleAllowed['role'])
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="sector" type="text" class="form-control input-big mt-md-0 mt-2" id="secteur" aria-describedby="" placeholder="@lang('wiki_profile.sector'), ..." value="{{$context['sector'] ?? ''}}">
                                        </div>
                                    </div>
                                    <small class="form-text text-muted font-weight-semibold mt-2">
                                        @lang('wiki_profile.fill_role_hint')
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-7">
                                @include('users.wizard-profile.fill-identity', [
                                    'firstname' => $user['firstname'],
                                    'lastname' => $user['lastname'],
                                ])
                            </div>
                            <div class="col-md-5 mt-4 mt-md-0">
                                @include('users.wizard-profile.fill-email', [
                                    'email' => $user['email'],
                                    'width' => 12
                                ])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-big success mb-3">@lang('wiki_profile.fill_postal_code_header')</label>
                                    <div class="row">
                                        <div class="col-md-3 pr-md-0">
                                            <div class="row align-items-center">
                                                <div class="col-lg-5 col-3">
                                                    <label>@lang('wiki_profile.fill_postal_code')</label>
                                                </div>
                                                <div class="col-lg-5 col-9">
                                                    <input value="{{old('postal_code', $context['postal_code'])}}" type="text" id="input-postal" name="postal_code"
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
                                        <div class="col-lg-9" id="geo-details">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row align-items-center mt-3 mt-lg-0">
                                    <div class="col-lg-4 col-3">
                                        <label class="label-big success mb-3">
                                            @lang('wiki_profile.structure')
                                        </label>
                                    </div>
                                    <div class="col-lg-8 col-9">
                                        <input name="structure"
                                               type="text"
                                               class="structure-auto-complete form-control input-big"
                                               id="structure"
                                               autocomplete="off"
                                               data-url="{{ route('profile.structure.search') }}"
                                               data-noresults-text="@lang('wiki_profile.no_results')"
                                               value="{{$context['structure'] ?? ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2 mb-2 mb-md-0">@lang('common.btn_save_edit')</button>
                                <button data-dismiss="modal" type="button" class="btn btn-outline-darkgreen text-dark px-5 py-2 ">@lang('common.btn_cancel')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        var wizardError = '{{ isset($errors) && !empty($errors->any()) ? 1 : 0 }}';
        var oldGeo = '{{ old('geo') !== null ? json_decode(old('geo'), true)['country_code'] : '' }}';
    </script>
@endsection
