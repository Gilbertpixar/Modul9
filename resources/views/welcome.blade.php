@extends('layouts.apps')

@section('content')

<div class="d-flex justify-content-center align-items-center"  style="height: 100vh; background-color: blue;" >
    <div class="p-5 bg-light rounded-3 border col-xl-3">
        <div class="mb-3 text-center">
            <i class="bi-hexagon-fill fs-1 text-primary"></i>
            <h4 class="fw-bold">{{ __('Employee Data Master') }}</h4>
        </div>
        <hr>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="col-md-12 mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <div class="text-danger"><small>{{ $message }}</small></div>
                @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <div class="text-danger"><small>{{ $message }}</small></div>
                @enderror
            </div>


            <div class="row">
                <div class="col-md-12 d-grid">
                    <button type="submit" class="btn btn-primary btn-lg mt-3"> <i class="bi bi-arrow-right-square me-2"></i>{{ __('Login') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
