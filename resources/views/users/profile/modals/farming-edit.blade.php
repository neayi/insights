<div class="modal fade exploitations-edit" id="exploitationsEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-edit mx-0 mx-sm-auto" role="document">
        <div class="modal-content p-md-3 p-1">
            <button type="button" class="close text-right d-block" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="material-icons">close</span>
            </button>
            <div class="modal-body pt-2">
                <div class="container-fluid">
                    <form id="form-update-description" action="{{ route('context.update.description') }}" method="POST">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-big mb-3">@lang('wiki_profile.my_farm_aim')</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                           <textarea name="description" rows="12" class="w-100">{!! strip_tags($context['description']) !!}</textarea>
                                        </div>
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
