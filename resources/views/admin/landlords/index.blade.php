@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.landlords.title')</h3>
    @can('landlord_create')
    <p>
        <a href="{{ route('admin.landlords.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>
    @endcan

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($landlords) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>@lang('global.landlords.fields.name')</th>
                        <th>@lang('global.landlords.fields.email')</th>
                        <th>@lang('global.landlords.fields.active')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($landlords) > 0)
                        @foreach ($landlords as $landlord)
                            <tr data-entry-id="{{ $landlord->id }}">
                                <td field-key='name'>{{ $landlord->landlord->name }}</td>
                                <td field-key='email'>{{ $landlord->landlord->email }}</td>
                                <td field-key='active'>{{ $landlord->active ? 'Yes' : 'No' }}</td>
                                <td>
                                    @can('landlord_edit')
                                    <a href="{{ route('admin.landlords.edit',[$landlord->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('landlord_delete')
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.landlords.destroy', $landlord->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

