<div class="form-group">
    <label class="mb-3">Merci de préciser votre localisation</label>
    <div class="row align-items-center">
        <div class="col-lg-12 col-md-12">
            <div class="form-row">
                <div class="form-group">
                    <label> Sélectionnez votre pays</label>
                    <select class="form-control input-big" id="select-country">
                        @foreach($geos as $country => $geo)
                            <option value="{{$country}}">{{$country}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @php $count = 0 @endphp
            @foreach($geos as $country => $countryGeos)
                <div class="div-country" id="div-{{$country}}" @if( $count > 0 ) style="display: none;" @endif>
                    @php $count++; @endphp
                    @foreach($countryGeos as $geo)
                        <div class="form-check">
                            <input name="geo" id="i-{{$geo['fields']['place_name']}}" type="radio" value="{{ json_encode($geo) }}">
                            <label for="i-{{$geo['fields']['place_name']}}" class="form-check-label">{{ $geo['fields']['place_name'].' ('.$geo['fields']['country_code'].')' }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>


