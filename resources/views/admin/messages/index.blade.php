@extends('layouts.admin')
@section('title', 'Pesan Kontak')
@section('heading', 'Pesan Kontak')

@section('content')
<div class="card-a">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6"><input type="search" name="q" value="{{ $filters['search'] }}" class="form-control" placeholder="Cari nama, email, subjek, atau isi pesan..."></div>
        <div class="col-md-4">
            <select name="status" class="form-select" aria-label="Status">
                <option value="">Semua status</option>
                @foreach(\App\Models\ContactMessage::STATUSES as $k => $label)<option value="{{ $k }}" @selected($filters['status'] === $k)>{{ $label }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-line flex-fill" type="submit">Cari</button>@if($filters['search'] || $filters['status'])<a class="btn btn-line" href="{{ route('admin.messages.index') }}">Reset</a>@endif</div>
    </form>
    @if($messages->isEmpty())
        <div class="empty-a"><i class="fas fa-envelope-open"></i>Tidak ada pesan.</div>
    @else
        <div class="tbl-wrap"><table class="tbl">
            <thead><tr><th>Pengirim</th><th>Subjek</th><th>Status</th><th>Diterima</th><th></th></tr></thead>
            <tbody>
            @foreach($messages as $m)
                <tr>
                    <td><div class="title" @if($m->status === 'new') style="color:var(--primary)" @endif>{{ $m->name }}</div><div class="sub">{{ $m->email }}</div></td>
                    <td>{{ $m->subject }}<div class="sub">{{ \Illuminate\Support\Str::limit($m->message, 70) }}</div></td>
                    <td><span class="pill {{ $m->status }}">{{ $m->status_label }}</span></td>
                    <td class="sub">{{ $m->created_at->translatedFormat('j M Y H:i') }}</td>
                    <td class="acts"><a class="btn-line btn-sm-x" href="{{ route('admin.messages.show', $m) }}">Buka</a>
                        @include('admin.partials.delete-form', ['action' => route('admin.messages.destroy', $m), 'confirm' => 'Hapus pesan dari '.$m->name.'?'])</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="mt-3">{{ $messages->links() }}</div>
    @endif
</div>
@endsection
