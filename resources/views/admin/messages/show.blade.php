@extends('layouts.admin')
@section('title', 'Pesan dari '.$message->name)
@section('heading', 'Detail Pesan')
@section('actions')<a href="{{ route('admin.messages.index') }}" class="btn btn-line btn-sm"><i class="fas fa-arrow-left me-1"></i>Semua pesan</a>@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-a">
            <h2>{{ $message->subject }}</h2>
            <dl class="row mb-3">
                <dt class="col-sm-3">Nama</dt><dd class="col-sm-9">{{ $message->name }}</dd>
                <dt class="col-sm-3">Email</dt><dd class="col-sm-9"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
                <dt class="col-sm-3">Telepon</dt><dd class="col-sm-9">{{ $message->phone ?: '-' }}</dd>
                <dt class="col-sm-3">Diterima</dt><dd class="col-sm-9">{{ $message->created_at->translatedFormat('l, j F Y H:i') }}</dd>
                <dt class="col-sm-3">Alamat IP</dt><dd class="col-sm-9">{{ $message->ip_address ?: '-' }}</dd>
            </dl>
            <div style="white-space:pre-wrap;background:#faf8f4;border:1px solid var(--line);border-radius:8px;padding:16px;">{{ $message->message }}</div>
            <div class="mt-3"><a class="btn btn-brand btn-sm" href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.$message->subject) }}"><i class="fas fa-reply me-1"></i>Balas lewat email</a></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-a">
            <h2>Status</h2>
            <form method="POST" action="{{ route('admin.messages.update', $message) }}">
                @csrf @method('PUT')
                <select name="status" class="form-select mb-3" aria-label="Status">
                    @foreach(\App\Models\ContactMessage::STATUSES as $k => $label)<option value="{{ $k }}" @selected($message->status === $k)>{{ $label }}</option>@endforeach
                </select>
                <button class="btn btn-brand w-100" type="submit">Simpan status</button>
            </form>
        </div>
        <div class="card-a">
            <h2>Hapus pesan</h2>
            @include('admin.partials.delete-form', ['action' => route('admin.messages.destroy', $message), 'confirm' => 'Hapus pesan ini secara permanen?', 'label' => 'Hapus pesan'])
        </div>
    </div>
</div>
@endsection
