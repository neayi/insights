<div class="form-group">
    <label class="mb-3">Merci de préciser votre localisation</label>
    <div class="row align-items-center">
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-5">
                        <label> Sélectionnez votre pays</label>
                    </div>
                    <div class="col-lg-7">
                        <select class="form-control input-big" id="select-country">
                            @foreach($geos as $country => $geo)
                                <option value="@json($geo)">{{$country}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


