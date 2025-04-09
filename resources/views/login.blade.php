<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="login-container">
    <h1>Admin Login</h1>

    {{-- Session Error --}}
    @if(session('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', app()->environment('local') ? 'test@example.com' : '') }}"
                   required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   value="{{ app()->environment('local') ? 'password' : '' }}"
                   required>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>
</div>
</body>
</html>
