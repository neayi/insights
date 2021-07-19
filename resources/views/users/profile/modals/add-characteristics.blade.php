<div class="modal fade caracteristiques-edit" id="caracteristiquesEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-edit mx-0 mx-sm-auto" role="document">
        <div class="modal-content p-md-3 p-1">
            <button type="button" class="close text-right d-block" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="material-icons">close</span>
            </button>
            <form id="form-update-characteristics" action="{{ route('context.update.characteristics') }}" method="POST">
                <div class="modal-body pt-2">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="info">
                                    Vous pouvez utiliser le bouton
                                    <span class="material-icons circle-icon mx-1">
                                     add
                                    </span>
                                    pour ajouter des productions ou caractéristiques
                                </p>
                            </div>
                        </div>
                        <!-- je suis principalement en -->
                        <div class="row mt-3 icon-checkboxes">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-big mb-3 mr-3">Je suis principalement en</label>
                                    <a class="material-icons circle-icon mx-1 search-type-c" data-type-pretty="Ajouter des productions :"
                                       data-toggle="modal" data-type="farming" data-target="#caracteristiqueSearch">
                                        add
                                    </a>
                                    <div class="circle-row d-flex flex-wrap">
                                    @foreach($farmingType as $farming)
                                            @if($farming['visible'] === 1 || in_array($farming['uuid'], $uuidsUserCharacteristics))
                                                <div class="circle-item">
                                                    @include('users.wizard-profile.icon-farming', [
                                                             'uuid' => $farming['uuid'],
                                                             'code' => $farming['code'],
                                                             'icon' => $farming['icon'],
                                                             'label' => $farming['pretty_page_label'],
                                                             'visible' => $farming['visible'],
                                                             'checked' => in_array($farming['uuid'], $uuidsUserCharacteristics)
                                                         ])
                                                </div>
                                            @endif
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- je suis en -->
                        <div class="row mt-5 icon-checkboxes">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-big mb-3 mr-3">Je suis en</label>
                                    <a class="material-icons circle-icon mx-1 search-type-c" data-type-pretty="Ajouter des caractéristiques à mon système :"
                                       href="#" data-toggle="modal" data-type="croppingSystem" data-target="#caracteristiqueSearch">
                                        add
                                    </a>
                                    <div class="circle-row d-flex flex-wrap">
                                        @foreach($croppingType as $farming)
                                            @if($farming['visible'] === 1 || in_array($farming['uuid'], $uuidsUserCharacteristics))
                                                <div class="circle-item">
                                                    @include('users.wizard-profile.icon-farming', [
                                                             'uuid' => $farming['uuid'],
                                                             'code' => $farming['code'],
                                                             'icon' => $farming['icon'],
                                                             'label' => $farming['pretty_page_label'],
                                                             'visible' => $farming['visible'],
                                                             'checked' => in_array($farming['uuid'], $uuidsUserCharacteristics)
                                                         ])
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-4 border-0">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2 mb-2 mb-md-0">Enregistrer les modifications</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-darkgreen text-dark px-5 py-2 ">Annuler</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
