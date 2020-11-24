@php
    $state = 'required';
    if(old('firstname') && !$errors->has('firstname') && !$errors->has('lastname')){
        $state = 'success';
    }
    if($firstname !== "" && $lastname !== ""){
        $state = 'success';
    }
@endphp

<div class="form-group">
    <label id="state-identity" class="label-big {{$state}} mb-3">Je m'appelle</label>
    <div class="form-row">
        <div class="col-md-5">
            <input type="text" name="firstname" value="{{old('firstname', $firstname)}}" class="input-identity form-control input-big" id="firstname" aria-describedby="" placeholder="Prénom">
        </div>
        <div class="col-md-7">
            <input type="text" name="lastname" value="{{old('lastname', $lastname)}}" class="input-identity form-control input-big" id="surname" aria-describedby="" placeholder="Nom">
        </div>
    </div>
    <small class="form-text text-muted font-weight-semibold mt-2">
        Nous sommes convaincus qu’une personne qui s’exprime en utilisant sa véritable identité engage sa réputation. Notre communauté devient plus responsable et bienveillante.
    </small>
</div>
