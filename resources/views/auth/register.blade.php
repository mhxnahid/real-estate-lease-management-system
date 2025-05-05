@extends('layouts.auth')

@section('styles')

@endsection

@section('content')
    <div class="auth-box reg-box">
        <h3 class="panel-title">@lang('global.app_registration')</h3>

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

        <form class="" role="form" method="POST" action="{{ url('/register') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label">@lang('global.app_name')</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">@lang('global.app_email')</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            {{-- <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="control-label">@lang('global.app_password')</label>
                <input id="password" type="password" class="form-control" name="password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password-confirm" class="control-label">@lang('global.app_confirm_password')</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div> --}}

            <div class="form-group">
                <div class="">
                    <button type="submit" class="btn btn-primary btn-block">
                        @lang('global.app_register')
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection