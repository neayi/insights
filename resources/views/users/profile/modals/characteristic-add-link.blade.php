<div class="modal fade caracteristique-add-link" id="caracteristiqueAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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
                                Ajouter une caractéristique "<span class="span-type"></span>"
                            </h3>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-12">
                            <form action="{{ route('profile.characteristic.create') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" class="input-type" id="i-type" value="">
                                <div class="input-group search-form mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-right-0">
                                            <span class="material-icons">
                                               add
                                            </span>
                                        </span>
                                    </div>
                                    <input data-type=""
                                           name="title"
                                           type="text"
                                           placeholder="Ajouter : une pratique, une culture, un matériel…"
                                           class="form-control pb-2 first-input">
                                </div>
                                <button type="submit" class="btn btn-dark-green text-white">
                                    Ajouter la caractéristique
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
