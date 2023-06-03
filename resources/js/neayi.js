require('./bootstrap');
require('bootstrap-select');
require('bootstrap-autocomplete');


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
    failedState(elem);
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
                    if( $(".avatar-block img").length === 0) {
                        location.reload();
                    }
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

$('#btn-show-practises').click(function (){
    var action = $(this).attr('action');
    if(action === 'show') {
        $(this).attr('action', 'hide');
        $(this).html('Afficher moins');
        $(".pratiques.edition .filled").css('-webkit-line-clamp', 'unset');
        return;
    }
    $(this).attr('action', 'show');
    $(this).html("Afficher tout l'historique");
    $(".pratiques.edition .filled").css('-webkit-line-clamp', '11');
});


$('.structure-auto-complete').autoComplete();

$('#search-characteristics').on("keyup change", function(){
    var elem = $(this);
    var type = $(this).attr('data-type');
    var search = $(this).val();
    $.ajax({
        url : elem.attr('data-action'),
        data: {type:type, search:search},
        success:function (data){
            $('#result-row').html(data);
        }
    });
});

$('.search-type-c').click(function(){
    $('#search-characteristics').attr('data-type', $(this).attr('data-type'));
    $('.input-type').val($(this).attr('data-type'));
    $('.span-type').html($(this).attr('data-type-pretty'));
});

$('#btn-remove-avatar').click(function (){
    $(this).parents('form').submit();
});

$('#input-postal').change(function (){
    var postal = $(this).val();
    $.ajax({
        url: '/geo',
        data: { postal_code : postal },
        success: function (data) {
            $('#geo-details').html(data);
            var elem = $('#state-postal');
            var found = $('#select-country').val();
            if(found != null) {
                succeedState(elem);
                return;
            }
            failedState(elem);
        }
    });
});

if(wizardError == '1') {
    var postal = $('#input-postal').val();
    $.ajax({
        url: '/geo',
        data: { postal_code : postal, country_selected: oldGeo },
        success: function (data) {
            $('#geo-details').html(data);
            var elem = $('#state-postal');
            var found = $('#select-country').val();
            if(found != null) {
                succeedState(elem);
                return;
            }
            failedState(elem);
        }
    });
}

$('#no-postal-code').click(function (){
    $(this).hide();
    $('#no_postal_code_input').val(1);
    $('#input-postal').val('');
    $('#input-postal').prop('disabled', true);
    $('#geo-details').html('');
    $('#fill-postal-code').show();
});

$('#fill-postal-code').click(function (){
    $(this).hide();
    $('#no_postal_code_input').val(0);
    $('#input-postal').prop('disabled', false);
    $('#select-country').prop('disabled', false);
    $('#no-postal-code').show();
});

