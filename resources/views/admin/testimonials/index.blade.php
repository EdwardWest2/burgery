@extends('layouts.admin')
@section('title', 'Testimoni')
@section('heading', 'Testimoni')
@section('actions')<a href="{{ route('admin.testimonials.create') }}" class="btn btn-brand btn-sm"><i class="fas fa-plus me-1"></i>Tambah testimoni</a>@endsection

@section('content')
<div class="card-a">
    <div class="flash err" style="background:#fff7e6;border-color:#f6d9a0;color:#7a5200;"><i class="fas fa-triangle-exclamation me-1"></i>Testimoni bawaan adalah <strong>contoh teks</strong>. Ganti dengan ulasan pelanggan asli (dengan izin mereka) atau nonaktifkan semuanya agar bagian ini tersembunyi di beranda.</div>
    @if($testimonials->isEmpty())
        <div class="empty-a"><i class="fas fa-comments"></i>Belum ada testimoni. Bagian testimoni di beranda tersembunyi.</div>
    @else
        <div class="tbl-wrap"><table class="tbl">
            <thead><tr><th>Nama</th><th>Isi</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($testimonials as $t)
                <tr>
                    <td class="title">{{ $t->name }}<div class="sub">{{ $t->role }}</div></td>
                    <td class="sub">{{ \Illuminate\Support\Str::limit($t->content, 90) }}</td>
                    <td><span class="pill {{ $t->is_active ? 'published' : 'off' }}">{{ $t->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="acts"><a class="btn-line btn-sm-x" href="{{ route('admin.testimonials.edit', $t) }}">Edit</a>
                        @include('admin.partials.delete-form', ['action' => route('admin.testimonials.destroy', $t), 'confirm' => 'Hapus testimoni ini?'])</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @endif
</div>
@endsection
