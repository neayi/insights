@extends('adminlte::page')

@section('title', __('pages.title_add_organization'))

@section('content_header')
    <h1>@lang('pages.title_add_organization')</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('pages.title_add_organization')</h3>
                    </div>
                    <form role="form" method="POST" action="{{route('organization.add')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="i-email">@lang('organizations.name') (*)</label>
                                        <input value="{{old('name')}}" type="text" class="form-control @error('name') is-invalid @enderror" id="i-email" placeholder="@lang('organizations.name')" name="name">
                                    </div>
                                    <div class="form-group">
                                        @error('name')
                                            <div class="alert alert-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input name="logo" type="file" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Logo de l'organisme</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="callout callout-info">
                                            <p>
                                                L'image sera redimensionné en 400 * 600
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @error('mine_type')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="address1">@lang('organizations.address1') (*)</label>
                                        <input value="{{old('address1')}}" type="text" name="address1" class="form-control @error('address1') is-invalid @enderror" id="address1" placeholder="@lang('organizations.address1')">
                                    </div>
                                    <div class="form-group">
                                        @error('address1')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="address2">@lang('organizations.address2')</label>
                                        <input value="{{old('address2')}}" type="text" name="address2" class="form-control @error('address2') is-invalid @enderror" id="address2" placeholder="@lang('organizations.address2')">
                                    </div>
                                    <div class="form-group">
                                        @error('address2')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="city">@lang('organizations.city') (*)</label>
                                        <input value="{{old('city')}}" type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="city" placeholder="@lang('organizations.city')">
                                    </div>
                                    <div class="form-group">
                                        @error('city')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="pc">@lang('organizations.pc') (*)</label>
                                        <input value="{{old('pc')}}" type="text" name="pc" class="form-control @error('pc') is-invalid @enderror" id="pc" placeholder="@lang('organizations.pc')">
                                    </div>
                                    <div class="form-group">
                                        @error('pc')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.js"></script>
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
@endsection
