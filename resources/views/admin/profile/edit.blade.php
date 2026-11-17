@extends('layouts.admin')
@section('title', 'Profil')
@section('heading', 'Profil & Password')

@section('content')
<div class="card-a" style="max-width:600px;">
    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf @method('PUT')
        <div class="mb-3"><label class="form-label">Username</label><input class="form-control" value="{{ $user->username }}" disabled><div class="hint">Username tidak dapat diubah dari sini. Gunakan perintah <code>php artisan burgery:admin --username=...</code> bila perlu.</div></div>
        <div class="mb-3"><label class="form-label" for="name">Nama tampilan *</label><input id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" maxlength="100" required></div>
        <div class="mb-4"><label class="form-label" for="email">Email *</label><input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" maxlength="150" required></div>
        <h2 class="h6 mb-3">Ganti password (opsional)</h2>
        <div class="mb-3"><label class="form-label" for="current_password">Password saat ini</label><input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password"></div>
        <div class="mb-3"><label class="form-label" for="password">Password baru</label><input id="password" name="password" type="password" class="form-control" autocomplete="new-password" minlength="8"><div class="hint">Minimal 8 karakter.</div></div>
        <div class="mb-4"><label class="form-label" for="password_confirmation">Ulangi password baru</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password"></div>
        <button class="btn btn-brand" type="submit">Simpan profil</button>
    </form>
</div>
@endsection
