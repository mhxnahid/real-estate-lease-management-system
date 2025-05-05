@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">@lang('global.app_dashboard')</div>

        <div class="panel-body">
            <div class="d-flex justify-content-between">
                @if ($totalLandlords !== null)
                    <div class="flex-item">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{ $totalLandlords }}</h3>
                                <p>Landlords</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-user"></i>
                            </div>
                            <a href="{{ route('admin.landlords.index') }}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif

                @if ($totalTenants !== null)
                    <div class="flex-item">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{ $totalTenants }}</h3>
                                <p>Tenants</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="{{ route('admin.tenants.index') }}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif

                @if ($totalProperties !== null)
                    <div class="flex-item">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{ $totalProperties }}</h3>
                                <p>Properties</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-building"></i>
                            </div>
                            <a href="{{ route('admin.properties.index') }}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif

                @if ($totalLeases !== null)
                    <div class="flex-item">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{ $totalLeases }}</h3>
                                <p>Leases</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-text"></i>
                            </div>
                            <a href="{{ route('admin.leases.index') }}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        .small-box-footer{
            display: none !important;
        }
    </style>
@endsection
