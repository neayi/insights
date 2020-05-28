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
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
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
            ],
            fnRowCallback: function(row, data) {
                var picture = '<img src="'+data[1]+'" style="width:150px;"/>';
                $('td', row).eq(1).html(picture);
            }
        });
    </script>
@stop
