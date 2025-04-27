@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.leases.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('global.leases.fields.property')</th>
                            <td field-key='property'>@if($lease->property)<a href="{{ route('admin.properties.show', ['property' => $lease->property->id]) }}">{{ $lease->property->name ?? '' }} ({{ $lease->property->address ?? '' }})@endif</a></td>
                        </tr>
                        <tr>
                            <th>@lang('global.leases.fields.tenant')</th>
                            <td field-key='tenant'>{{ $lease->tenant->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.leases.fields.landlord')</th>
                            <td field-key='landlord'>{{ $lease->landlord->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.leases.fields.document_ref')</th>
                            <td field-key='document_ref'>
                                @if($lease->document_ref)
                                    <a href="{{ asset('storage/' . $lease->document_ref) }}" target="_blank">@lang('global.app_download')</a>
                                @else
                                    @lang('global.app_no_document')
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('global.leases.fields.lease_start')</th>
                            <td field-key='lease_start'>{{ $lease->lease_start }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.leases.fields.lease_end')</th>
                            <td field-key='lease_end' style="color: {{ $lease->lease_end && $lease->lease_end < now() ? 'red' : 'inherit' }}">{{ $lease->lease_end }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.leases.fields.tenant_accepted')</th>
                            <td field-key='tenant_accepted'>{{ $lease->tenant_accepted ? 'Yes' : 'No' }}</td>
                        </tr>
                        @if(!$lease->tenant_accepted)
                        @can('lease_accept')
                        <tr>
                            <th></th>
                            <td>
                                <div>Read lease terms carefully as written in the agreement document.</div>
                                {!! Form::open(['route' => ['admin.leases.accept_lease', 'lease' => $lease->id], 'method' => 'POST', 'style' => 'display:inline;']) !!}
                                    {!! Form::submit(trans('global.leases.accept_proposal'), ['class' => 'btn btn-success', 'onclick' => "return confirm('" . trans('global.app_are_you_sure') . "');"]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        @endcan
                        @endif
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.leases.index') }}" class="btn btn-default">@lang('global.app_back_to_list')</a>
        </div>
    </div>
@stop