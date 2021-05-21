<div class="row mt-1 align-items-center icon-checkboxes">
    <div class="col-md-12">
        @if(!empty($characteristics))
            <form action="{{ route('profile.characteristic.add') }}" method="POST">
                @csrf
                @foreach($characteristics as $farming)
                    <div class="form-group">
                        <input id="sc-{{$farming['uuid']}}" type="checkbox" name="farming_type[]" value="{{$farming['uuid']}}"/>
                        <label for="sc-{{$farming['uuid']}}" class="d-flex align-items-center">
                            @if($farming['icon'] !== null)
                                <img src="{{asset('storage/'.str_replace('public/', '', $farming['icon']))}}" class="rounded-circle mb-2"/>
                            @endif
                            <span class="d-block ml-4"> {{ $farming['pretty_page_label'] }} </span>
                        </label>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-dark-green text-white">
                    Ajouter à mon profil
                </button>
            </form>
        @else
            <div class="row mt-3">
                <div class="col-md-12">
                    <form action="{{ route('profile.characteristic.create') }}" method="POST">
                        @csrf
                        <p>
                            Malheuresement aucun résultat ne correspond à votre recherche 1
                            Vous pouvez créer la caractéristique, elle sera automatique associée à votre profil
                        </p>
                        <input type="hidden" name="type" id="i-type" value="{{$type}}">
                        <input type="hidden" name="title" id="i-title" value="{{$search}}">
                        <button type="submit" class="btn btn-dark-green text-white">
                            Ajouter la caractéristique {{ ucfirst($search) }}
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
