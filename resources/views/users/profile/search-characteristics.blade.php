<div class="row mt-1 align-items-center icon-checkboxes">
    <div class="col-md-12">
        @if(!empty($characteristics))
            @foreach($characteristics as $farming)
                <div class="form-group">
                    <input type="checkbox" id="suggestion2" />
                    <label for="suggestion2" class="d-flex align-items-center">
                        <img src="{{asset('storage/'.str_replace('public/', '', $farming['icon']))}}" class="rounded-circle mb-2"/>
                        <span class="d-block ml-4"> {{ $farming['pretty_page_label'] }} </span>
                    </label>
                </div>
            @endforeach
            <button class="btn btn-dark-green text-white">
                Ajouter à mon profil
            </button>
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
