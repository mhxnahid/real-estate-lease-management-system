@extends('layouts.auth')

@section('styles')

@endsection

@section('content')
    <div class="auth-box reg-box">
        <h3 class="panel-title">@lang('global.app_registration')</h3>

        <div class="alert alert-success">
            <p>Please check your email and verify.</p>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('global.app_whoops')</strong> @lang('global.app_there_were_problems_with_input'):
                <br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
@endsection