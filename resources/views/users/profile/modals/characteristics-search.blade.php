<div class="modal fade caracteristique-search" id="caracteristiqueSearch" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg mx-0 mx-sm-auto mt-5" role="document">
        <div class="modal-content p-md-3 p-1">
            <button type="button" class="close text-right d-block" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="material-icons">close</span>
            </button>
            <div class="modal-body pt-2">
                <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="font-weight-bold">
                                    <span class="span-type"></span>
                                </h3>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-12">
                                <div class="input-group search-form mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-right-0">
                                            <span class="material-icons">
                                               search
                                            </span>
                                        </span>
                                    </div>
                                    <input id="search-characteristics"
                                           data-type=""
                                           type="text" data-action="{{ route('profile.characteristics.search') }}"
                                           placeholder="Rechercher : une pratique, une culture, un matériel…"
                                           class="form-control pb-2 first-input">
                                </div>
                            </div>
                        </div>
                        <div id="result-row">

                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
