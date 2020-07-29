@extends('adminlte::page')

@section('title', __('pages.title_list_users'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('pages.title_list_users')</h3>
                    </div>
                    <div class="card-body">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>@lang('users.table.avatar')</th>
                                    <th>@lang('users.table.identity')</th>
                                    <th>@lang('users.table.email')</th>
                                    <th>@lang('users.table.state')</th>
                                    <th>@lang('users.table.last_login')</th>
                                    <th>@lang('users.table.actions')</th>
                                    <th>@lang('users.table.actions')</th>
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
@stop



@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(function () {

        var editActionMessage = '{{__('users.action.edit')}}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var route = '{{route('users.list.datatable', ['id' => $organization_id])}}';
        $('.data-table').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            searching: false,
            ordering:  false,
            ajax: {
                url: route,
                type: 'POST'
            },
            iDisplayLength: 10,
            lengthChange: false,
            columns: [
                {"name": "logo"},
                {"name": "Name"},
                {"name": "email"},
                {"name": "state"},
                {"name": "Action"},
                {"name": "id", 'visible':false},
                {"name": "link", 'visible':false},
            ],
            fnRowCallback: function(row, data) {
                if(data[6] != null) {
                    var picture = '<img style="height:34px; width:34px;" class="user-image img-circle elevation-2" src="' + data[6] + '"/>';
                    $('td', row).eq(0).html(picture);
                }
                var action = '<a href="'+data[7]+'" class="btn btn-block btn-outline-primary btn-xs" style="cursor:pointer;">' +
                    '<i class="far fa-plus-square"></i>'+editActionMessage+'</a>'
                $('td', row).eq(3).html(action);
            }
        });
    });
    </script>
@stop
