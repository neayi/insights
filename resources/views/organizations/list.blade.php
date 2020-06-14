@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('pages.title_list_organization')</h3>
                    </div>
                    <div class="card-body">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Logo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalInvitation" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form role="form" action="{{route('organization.users.prepare-invite')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Inviter des utilisateurs dans l'organisation</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="" id="i-organization-id" name="organization_id">
                        <div class="form-group">
                            <label for="list-users">Ajouter des utilisateurs : un email par ligne</label>
                            <textarea name="users" id="list-users" class="form-control" rows="5"></textarea>
                        </div>
                        <hr>
                        <p>Ou importer un fichier</p>
                        <div class="form-group">
                            <div class="custom-file">
                                <input name="users" type="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Seulement CSV</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" disabled class="btn btn-primary" id="button-form-invite-user" value="Inviter">
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop



@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var routeOrg = '{{route('organization.list.datatable')}}';
        $('.data-table').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            searching: false,
            ordering:  false,
            ajax: {
                url: routeOrg,
                type: 'POST'
            },
            iDisplayLength: 10,
            lengthChange: false,
            columns: [
                {"searchable": false,"name": "Name"},
                {"name": "Logo"},
                {"name": "Action"},
                {"name": "id", 'visible':false},
            ],
            fnRowCallback: function(row, data) {
                if(data[1] != null) {
                    var picture = '<img src="' + data[1] + '" style="width:150px;"/>';
                    $('td', row).eq(1).html(picture);
                }
                var action = '<button type="button" class="open-invite-modal btn btn-block btn-outline-primary btn-xs" data-organization_id="'+data[3]+'" style="cursor:pointer;" "><i class="far fa-plus-square"></i> Inviter des utilisateurs</button>'
                action += '<a href="organization/'+data[3]+'/users" class="btn btn-block btn-outline-primary btn-xs" style="cursor:pointer;" "><i class="far fa-user"></i>Voir les utilisateurs</a>'
                $('td', row).eq(2).html(action);
            }
        });


        $('#modalInvitation').modal({show:false});

        $('.data-table').on('click', '.open-invite-modal',function () {
            $('#button-form-invite-user').prop('disabled', true);
            $('#i-organization-id').val($(this).data('organization_id'));
            $('#list-users').val('');
            $('#modalInvitation').modal('show');
        });

        $('#list-users').keyup(function () {
            if($(this).val() != "") {
                $('#button-form-invite-user').prop('disabled', false);
            }else{
                $('#button-form-invite-user').prop('disabled', true);
            }
        });
    });
    </script>
@stop
