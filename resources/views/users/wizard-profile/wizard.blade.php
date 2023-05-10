@extends('layouts.neayi.master-no-menus')

@section('title', __('pages.wizard-profile'))

@section('content')

<form class="form" role="form" method="post" action="{{ route('wizard.profile.process') }}">
@csrf
    <div class="row py-5">
        @if(empty($errors->any()))
            <div class="col-lg-8 offset-lg-2 col-12" id="msg">
                <span class="font-weight-bold text-dark-green text-big d-block mb-4">Merci !</span>
                <p class="mb-4">
                    @lang('wiki_profile.fill_profile_hint')
                </p>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="col-lg-8 offset-lg-2 col-12" id="msg-err">
                <p class="mb-4" style="color: red;">
                    @lang('wiki_profile.fill_profile_almost_done')
                </p>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-3 offset-lg-2">
            @include('users.wizard-profile.select-user-role')
        </div>
        <div class="col-lg-6">
            @include('users.wizard-profile.fill-identity')
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-3  offset-lg-2">
            @include('users.wizard-profile.fill-postal-code')
        </div>
        <div class="col-lg-6" id="geo-details">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-12 offset-lg-2">
            @include('users.wizard-profile.fill-email')
        </div>
    </div>
    <div class="row mt-5 icon-checkboxes" id="select-farming">
        <div class="col-lg-8 offset-lg-2" id="select-main-c">
            <div class="form-group">
                <label class="label-big mb-3">@lang('wiki_profile.fill_profile_production')</label>
                <div class="circle-row d-flex flex-wrap">
                    @php $displayMore = false; @endphp
                    @foreach($farmingTypeMain as $farming)
                        @php
                            if(isset($farming['main']) && !$farming['main']){
                                $displayMore = true;
                            }
                        @endphp
                        <div class="icon-characteristics circle-item" style="@if(isset($farming['main']) && !$farming['main']) display:none; @endif">
                            @include('users.wizard-profile.icon-farming', [
                                 'uuid' => $farming['uuid'],
                                 'code' => $farming['code'],
                                 'icon' => $farming['icon'],
                                 'label' => $farming['pretty_page_label'],
                                 'main' => $farming['main']
                             ])
                        </div>
                    @endforeach
                    @if($displayMore)
                        <div class="circle-item" id="more-main-production">
                            <label for="myCheckbox23">
                                <img src="{{asset('icons/More.svg')}}" class="rounded-circle mb-2" />
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8 offset-lg-2" id="select-cdc">
            <div class="form-group">
                <label class="label-big mb-3">@lang('wiki_profile.fill_profile_specs')</label>
                <div class="circle-row d-flex flex-wrap">
                @php $displayMore = false; @endphp
                @foreach($croppingTypeMain as $farming)
                        @php
                            if(isset($farming['main']) && !$farming['main']){
                                $displayMore = true;
                            }
                        @endphp
                        <div class="icon-characteristics circle-item" style="@if(isset($farming['main']) && !$farming['main']) display:none; @endif">
                            @include('users.wizard-profile.icon-farming', [
                                     'uuid' => $farming['uuid'],
                                     'code' => $farming['code'],
                                     'icon' => $farming['icon'],
                                     'label' => $farming['pretty_page_label'],
                                     'main' => $farming['main']
                                 ])
                        </div>
                    @endforeach
                    @if($displayMore)
                        <div class="circle-item" id="more-main-cropping">
                            <label for="myCheckbox23">
                                <img src="{{asset('icons/More.svg')}}" class="rounded-circle mb-2" />
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 offset-lg-2 col-md-4 mb-5">
            <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2">@lang('common.btn_validate')</button>
        </div>
    </div>
</form>
@endsection
