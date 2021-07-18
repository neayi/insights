<div class="row mt-1 align-items-center icon-checkboxes">
    <div class="col-md-12">
        @if(!empty($pages))
            @foreach($pages as $page)
                <form action="{{ route('profile.characteristic.add') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input id="sc-{{$page['page_id']}}" checked type="checkbox" name="farming_type[]" value="{{$page['page_id']}}"/>
                        <div class="row">
                            <div class="col-6">
                                <label for="sc-{{$page['page_id']}}" class="d-flex align-items-center">
                                    <span class="d-block ml-4"> {{ $page['title'] }} </span>
                                </label>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-dark-green text-white">
                                    Ajouter à mon profil
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach
        @else
            <div class="row mt-3">
                <div class="col-md-12">
                    <form action="{{ route('profile.characteristic.create') }}" method="POST">
                        @csrf
                        <p>
                            Malheuresement aucun résultat ne correspond à votre recherche !<br/>
                            Vous pouvez créer la caractéristique, elle sera automatique associée à votre profil
                        </p>
                        <input type="hidden" name="type" id="i-type" value="{{$type}}">
                        <input type="hidden" name="title" id="i-title" value="{{$search}}">
                        <button type="submit" class="btn btn-dark-green text-white">
                            Ajouter la caractéristique {{ $search }}
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
