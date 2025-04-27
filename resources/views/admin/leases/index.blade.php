@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.leases.title')</h3>
    @can('lease_create')
    <p>
        <a href="{{ route('admin.leases.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>
    @endcan

    <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.leases.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('global.app_all')</a></li> |
            <li><a href="{{ route('admin.leases.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('global.app_trash')</a></li>
        </ul>
    </p>
    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($leases) > 0 ? 'datatable' : '' }} @can('lease_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                    <tr>
                        @can('lease_delete')
                            @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan

                        <th>@lang('global.leases.fields.property')</th>
                        <th>@lang('global.leases.fields.tenant')</th>
                        <th>@lang('global.leases.fields.landlord')</th>
                        <th>@lang('global.leases.fields.lease_start')</th>
                        <th>@lang('global.leases.fields.lease_end')</th>
                        <th>@lang('global.leases.fields.tenant_accepted')</th>
                        <th>@lang('global.app_view')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($leases) > 0)
                        @foreach ($leases as $lease)
                            <tr data-entry-id="{{ $lease->id }}">
                                @can('lease_delete')
                                    @if ( request('show_deleted') != 1 )<td></td>@endif
                                @endcan

                                <td field-key='property'>{{ $lease->property->name ?? '' }}</td>
                                <td field-key='tenant'>{{ $lease->tenant->name ?? '' }}</td>
                                <td field-key='landlord'>{{ $lease->landlord->name ?? '' }}</td>
                                <td field-key='lease_start'>{{ $lease->lease_start }}</td>
                                <td field-key='lease_end' style="color: {{ $lease->lease_end && $lease->lease_end < now() ? 'red' : 'inherit' }}">{{ $lease->lease_end }}</td>
                                <td field-key='tenant_accepted'>{{ $lease->tenant_accepted ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('admin.leases.show', [$lease->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                </td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.leases.restore', $lease->id])) !!}
                                    {!! Form::submit(trans('global.app_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.leases.perma_del', $lease->id])) !!}
                                    {!! Form::submit(trans('global.app_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>
                                @else
                                <td>
                                    @can('lease_edit')
                                    <a href="{{ route('admin.leases.edit',[$lease->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    <!-- @can('lease_delete')
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.leases.destroy', $lease->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan -->
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('_lease_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.leases.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection