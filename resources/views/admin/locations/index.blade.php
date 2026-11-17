@extends('layouts.admin')
@section('title', 'Lokasi')
@section('heading', 'Lokasi')
@section('actions')<a href="{{ route('admin.locations.create') }}" class="btn btn-brand btn-sm"><i class="fas fa-plus me-1"></i>Tambah lokasi</a>@endsection

@section('content')
<div class="card-a">
    <p class="hint mb-3">Lokasi tambahan tampil di halaman Kontak. Alamat utama diatur di <a href="{{ route('admin.settings.edit') }}">Pengaturan</a>.</p>
    @if($locations->isEmpty())
        <div class="empty-a"><i class="fas fa-location-dot"></i>Belum ada lokasi tambahan.</div>
    @else
        <div class="tbl-wrap"><table class="tbl">
            <thead><tr><th>Nama</th><th>Alamat</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($locations as $l)
                <tr>
                    <td class="title">{{ $l->name }}</td>
                    <td class="sub">{{ \Illuminate\Support\Str::limit($l->address, 70) }}</td>
                    <td><span class="pill {{ $l->is_active ? 'published' : 'off' }}">{{ $l->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="acts"><a class="btn-line btn-sm-x" href="{{ route('admin.locations.edit', $l) }}">Edit</a>
                        @include('admin.partials.delete-form', ['action' => route('admin.locations.destroy', $l), 'confirm' => 'Hapus lokasi "'.$l->name.'"?'])</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @endif
</div>
@endsection
