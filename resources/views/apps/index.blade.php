@extends('layouts.app', ['title' => __('Settings')])
@section('js')
<script src="https://gumroad.com/js/gumroad.js"></script>
<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script type="text/javascript">
	Paddle.Setup({ 
        vendor: 49978,
         });
</script>
<script>
    var  email="{{ auth()->user()->email}}";
    function openPaddle(id){
        Paddle.Checkout.open({
			product: id,
			email: email
		});
    }
    function showPlugins(filterPlugins){
        $('.plugin-all').hide();
        var toShow='.plugin-'+filterPlugins;
        $(toShow).show();
    }
</script>
@endsection
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--9">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h3 class="mb-0">{{ __('Apps') }}</h3>
                            <p>{{__('Apps from community are not part of the All-Access Pass.')}}</p>
                        </div>
                        @if (config('settings.is_demo') | config('settings.is_demo')) 
                        <div class="col-6 text-right">
                            <a  onclick="alert('Disabled in demo')" class="btn btn-sm btn-success text-white">{{ __('Add new') }}</a>
                        </div>
                        @else
                            <div class="col-6 text-right">
                                <a  onclick="$('#appupload').click();" class="btn btn-sm btn-success text-white">{{ __('Add new') }}</a>
                            </div>
                        @endif
                        
                    </div>
                </div>
                <div class="card-body">
                    
                    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-text" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0 active" onclick="showPlugins('all')" id="tabs-text-1-tab" data-toggle="tab" href="#tabs-text-1" role="tab" aria-controls="tabs-text-1" aria-selected="true">{{ __('All') }}</a>
                        </li>
                         <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0"  onclick="showPlugins('installed')" id="tabs-text-2-tab" data-toggle="tab" href="#tabs-text-2" role="tab" aria-controls="tabs-text-2" aria-selected="false">{{ __('Installed') }}</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0"  onclick="showPlugins('tools')" id="tabs-text-2-tab" data-toggle="tab" href="#tabs-text-2" role="tab" aria-controls="tabs-text-2" aria-selected="false">{{ __('Tools') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0"  onclick="showPlugins('themes')" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-t" role="tab" aria-controls="tabs-text-t" aria-selected="false">{{ __('Themes')}}</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0"  onclick="showPlugins('payments')"  id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">{{ __('Payments') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" onclick="showPlugins('subscriptions')" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">{{ __('Subscriptions') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" onclick="showPlugins('apps')" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">{{ __('Mobile apps') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" onclick="showPlugins('languages')" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">{{ __('Languages') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0"  onclick="showPlugins('community')" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">{{ __('Community') }}</a>
                        </li>
                      </ul>
                      <hr />
                    
                    @include('partials.flash')
                    <form method="post" enctype="multipart/form-data">
                        @csrf
                        <div style="display: none">
                            <input name="appupload" type="file" class="" id="appupload" accept=".zip,.rar,.7zip"   onchange="form.submit()">
                        </div>
                    </form>

                    <div class="row">
                        @if(empty($apps))
                        <p>
                            {{ __("There are no apps at the moment")}}
                        </p>
                        @endif
                        @foreach ($apps as $app)
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mt-3 plugin-all @if ($app->installed) {{ 'plugin-installed'}} @endif <?php  foreach ($app->category as $cat){echo "plugin-".$cat." ";} ?>">
                            <div class="card" style="width: 18rem;">
                                <img class="card-img-top" src="{{ $app->image }}" alt="{{ $app->name }}">
                                <div class="card-body">
                                <h5 class="card-title">{{ $app->name }} @if ($app->installed)<span class="small text-green">{{ __('installed')}} v{{$app->version}}</span>@endif</h5>
                                <p class="card-text">{{ $app->description }}</p>
                                @if ($app->installed)
                                    <a href="{{ route('settings.index') }}" class="btn btn-sm btn-outline-success">{{ __('Settings')}}</a>
                                    @if ($app->updateAvailable)
                                        <a target="_blank" href="{{ $app->link }}@if(strlen(config('settings.extended_license_download_code'))>0)/{{ config('settings.extended_license_download_code') }}@endif" class="btn btn-sm btn-outline-primary">{{ __('Update available')}}</a>
                                    @endif
                                   <!-- <a href="{{ route('apps.remove',[$app->alias]) }}" class="btn btn-sm btn-outline-danger">{{ __('Delete')}}</a>-->
                                    @if ($app->price=="Free")
                                        <p class="card-text mt-2">{{__('Price')}}: 0$</p>
                                    @else
                                        <p class="card-text mt-2">{{__('Price')}}: {{ $app->price }}</p>
                                    @endif
                                @else
                                @if (isset($app->gr))
                                    @if(strlen(config('settings.extended_license_download_code'))>0)
                                        <a class="gumroad-button" href="https://gumroad.com/l/{{$app->gr}}/{{ config('settings.extended_license_download_code') }}">
                                    @else
                                        <a class="gumroad-button" href="https://gumroad.com/l/{{$app->gr}}">
                                    @endif
                                    
                                        @if ($app->price=="Free")
                                            {{ __('Download for free') }}
                                        @else
                                            {{ __('Buy now')." - ".$app->price }}
                                        @endif
                                    </a> 
                                 
                                @elseif (isset($app->paddle))
                                    <a style="color:#ffffff" onclick="openPaddle({{$app->paddle}})" class="btn btn-primary">{{ __('Buy now')." - ".$app->price }}</a>
                                    
                                @else
                                    <a target="_blank" href="{{ $app->link }}" class="btn btn-primary">{{ __('Buy now')." - ".$app->price }}</a>
                                @endif
                               
                                @endif
                                
                                </div>
                            </div>  
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection