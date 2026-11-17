<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk Admin - {{ setting('brand_name', 'Burgery') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}"/>
</head>
<body class="admin-login">
<div class="login-box">
    <div class="brand mb-1">{{ brand_wordmark() }}</div>
    <p class="text-muted mb-4">Masuk ke panel admin</p>

    @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('admin.login.attempt') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" autocomplete="username" autofocus required>
            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
            <label class="form-check-label" for="remember">Ingat saya di perangkat ini</label>
        </div>
        <button type="submit" class="btn btn-brand w-100">Masuk</button>
    </form>
    <p class="text-center mt-4 mb-0"><a href="{{ route('home') }}"><i class="fas fa-arrow-left me-1"></i>Kembali ke website</a></p>
</div>
</body>
</html>
