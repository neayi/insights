require('./bootstrap');
require('bootstrap-select');

// modal login
$("#show_hide_password a.eye").on('click', function(event) {
    event.preventDefault();
    if($('#show_hide_password input').attr("type") == "text"){
        $('#show_hide_password input').attr('type', 'password');
        $('#show_hide_password span').text( "visibility" );
    }else if($('#show_hide_password input').attr("type") == "password"){
        $('#show_hide_password input').attr('type', 'text');
        $('#show_hide_password span').text( "visibility_off" );
    }
});


$('#input-role').change(function (){
    var elem = $('#state-role');
    succeedState(elem);
    if($(this).val() !== "") {
        succeedState(elem);
        return;
    }
    failedState(elem)
});

$('#input-postal').change(function (){
    var elem = $('#state-postal');
    var postal = $(this).val();
    const regex = /^((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$/;
    const found = postal.match(regex);
    if(found != null) {
        succeedState(elem);
        return;
    }
    failedState(elem)
});

$('#input-email').change(function (){
    var elem = $('#state-email');
    var email = $(this).val();
    const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    const found = email.match(regex);
    if(found != null) {
        succeedState(elem);
        return;
    }
    failedState(elem)
});

$('.input-identity').change(function (){
    var elem = $('#state-identity');
    if($('#surname').val() != "" && $('#firstname').val() != ""){
        succeedState(elem);
        return;
    }
    failedState(elem);
});

$('#more-main-production').click(function (){
   $(this).fadeOut();
   $('#second-row-main-production').slideDown();
});

$('#more-main-cropping').click(function (){
    $(this).fadeOut();
    $('#second-row-main-cropping').slideDown();
});

function succeedState(elem) {
    elem.removeClass('required');
    elem.addClass('success');
}

function failedState(elem) {
    elem.addClass('required');
    elem.removeClass('success');
}
