@php
    $state = 'required';
    if(old('email') && !$errors->has('email')){
        $state = 'success';
    }
    if($email !== ""){
        $state = 'success';
    }
@endphp
<div class="form-group">
    <label id="state-email" class="label-big {{$state}} mb-3">Mon email</label>
    <div class="form-row">
        <div class="col-md-{{ isset($width) ? $width : 5 }}">
            <input type="text" name="email" value="{{old('email', $email)}}" class="form-control input-big" id="input-email" autocomplete="nope" aria-describedby="" placeholder="email">
            <small class="form-text text-muted font-weight-semibold mt-2">
                Nous avons besoin de votre email pour vous notifier quand une page évolue par exemple.
                Nous ne vous enverrons pas de mail non sollicité, et vous pourrez à tout moment régler le type de notifications
                que vous souhaitez recevoir.
            </small>
        </div>
    </div>
</div>
