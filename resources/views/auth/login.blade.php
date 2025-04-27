@extends('layouts.auth')

@section('styles')

@endsection

@section('content')
<div class="auth-box login-box">
        <h3 class="panel-title" style="">{{ ucfirst(config('app.name')) }} @lang('global.app_login')</h3>
        
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

        <form method="POST" action="{{ url('login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label>@lang('global.app_email')</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>@lang('global.app_password')</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> @lang('global.app_remember_me')
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    @lang('global.app_login')
                </button>
            </div>

            <div class="form-group text-center">
                <a href="{{ route('auth.password.reset') }}">@lang('global.app_forgot_password')</a><br><br>
                <a href="{{ route('auth.register') }}" style="font-size: 2rem;">@lang('global.app_registration')</a>
            </div>
        </form>
    </div>
@endsection