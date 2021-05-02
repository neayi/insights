require('./bootstrap');
require('bootstrap-select');


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

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
   $('#select-main-c .icon-characteristics').fadeIn();
});

$('#more-main-cropping').click(function (){
    $(this).fadeOut();
    $('#select-cdc .icon-characteristics').fadeIn();
});

function succeedState(elem) {
    elem.removeClass('required');
    elem.addClass('success');
}

function failedState(elem) {
    elem.addClass('required');
    elem.removeClass('success');
}


$('.picture_upload').click(function(){
    $('#fileinput').trigger('click');
});


$("#fileinput").change(function(){
    var fd = new FormData();
    var files = $('#fileinput')[0].files;

    if(files.length > 0 ){
        fd.append('file',files[0]);

        $.ajax({
            url: '/update-avatar',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response != 0){
                    $(".avatar-block img").attr("src", response);
                }
            },
        });
    }
});

$( "#form-update-description-btn" ).click(function() {
    $( "#form-update-description" ).submit();
});

$('#form-update-description').submit(function () {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(response){
            $('#dev-description').html(response);
            $('#exploitationsEdit').modal('hide');
        }
    })
    return false;
});

$('#form-update-main-data').submit(function () {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(response){
            $('#headerEdit').modal('hide');
            location.reload();
        }
    })
    return false;
});


$('#form-update-characteristics').submit(function () {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(response){
            $('#caracteristiquesEdit').modal('hide');
            location.reload();
        }
    })
    return false;
});

var loadAsyncDivs = $('[show-async]');
loadAsyncDivs.each(function (key, item){
    $.ajax({
       url: $(item).attr('show-async'),
       type: 'GET',
       success: function (html){
           $(item).html(html);
       }
    });
});
