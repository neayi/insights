@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>@lang('pages.title_add_organization')</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('pages.title_add_organization')</h3>
                    </div>
                    <form role="form" action="" method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('organizations.name') (*)</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="@lang('organizations.name')">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">@lang('organizations.picture')</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">@lang('common.btn_chose_file')</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                Best 400 * 600 px
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('organizations.name') (*)</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="@lang('organizations.name')">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('organizations.name') (*)</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="@lang('organizations.name')">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('organizations.name') (*)</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="@lang('organizations.name')">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('organizations.name') (*)</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="@lang('organizations.name')">
                            </div>
                        </div>
                        <div class="card-footer fa-pull-right">
                            <button type="submit" class="btn btn-primary">@lang('common.btn_confirm')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
    <script> console.log('Hi!'); </script>
@stop
