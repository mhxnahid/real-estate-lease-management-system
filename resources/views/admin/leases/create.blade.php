@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.leases.title')</h3>
    <form method="POST" action="{{ route('admin.leases.store') }}" enctype="multipart/form-data">
        @csrf

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('property_id', trans('global.leases.fields.property').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('property_id', $properties->pluck('name', 'id'), old('property_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('property_id'))
                        <p class="help-block">
                            {{ $errors->first('property_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('tenant_id', trans('global.leases.fields.tenant').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('tenant_id', $tenants->pluck('name', 'id'), old('tenant_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('tenant_id'))
                        <p class="help-block">
                            {{ $errors->first('tenant_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('lease_start', trans('global.leases.fields.lease_start').'*', ['class' => 'control-label']) !!}
                    {!! Form::date('lease_start', old('lease_start'), ['class' => 'form-control', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('lease_start'))
                        <p class="help-block">
                            {{ $errors->first('lease_start') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('lease_end', trans('global.leases.fields.lease_end').'*', ['class' => 'control-label']) !!}
                    {!! Form::date('lease_end', old('lease_end'), ['class' => 'form-control', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('lease_end'))
                        <p class="help-block">
                            {{ $errors->first('lease_end') }}
                        </p>
                    @endif
                </div>
            </div>
        <div class="row">
            <div class="col-xs-12 form-group">
                <div>Upload a lease agrement document that clearly states the necessary data of the property and the lease terms. The terms can't be changed later.</div>
                {!! Form::label('document_ref', trans('global.leases.fields.document_ref').'*', ['class' => 'control-label']) !!}
                {!! Form::file('document_ref', ['class' => 'form-control', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('document_ref'))
                    <p class="help-block">
                        {{ $errors->first('document_ref') }}
                    </p>
                @endif
            </div>
        </div>
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

