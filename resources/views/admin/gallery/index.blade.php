@extends('layouts.admin')
@section('title', 'Galeri')
@section('heading', 'Galeri')

@section('content')
<div class="card-a">
    <h2>Unggah foto</h2>
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.gallery.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4"><label class="form-label" for="images">Foto (boleh lebih dari satu) *</label><input id="images" type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/webp" multiple required><div class="hint">JPG, PNG, WebP, maks. 3 MB per foto.</div></div>
        <div class="col-md-3"><label class="form-label" for="caption">Caption</label><input id="caption" name="caption" class="form-control" maxlength="150" value="{{ old('caption') }}"><div class="hint">Dipakai untuk semua foto yang diunggah sekaligus; ubah per foto nanti.</div></div>
        <div class="col-md-3"><label class="form-label" for="alt_text">Alt text</label><input id="alt_text" name="alt_text" class="form-control" maxlength="200" value="{{ old('alt_text') }}"></div>
        <div class="col-md-2 d-flex align-items-end"><button class="btn btn-brand w-100" type="submit"><i class="fas fa-upload me-1"></i>Unggah</button></div>
    </form>
</div>
<div class="card-a">
    @if($photos->isEmpty())
        <div class="empty-a"><i class="fas fa-images"></i>Galeri masih kosong.</div>
    @else
        <div class="tbl-wrap"><table class="tbl">
            <thead><tr><th>Foto</th><th>Caption</th><th>Alt text</th><th>Urutan</th><th></th></tr></thead>
            <tbody>
            @foreach($photos as $p)
                <tr>
                    <td><img class="thumb" src="{{ $p->image_url }}" alt="{{ $p->alt_text }}" style="width:84px;height:60px"></td>
                    <td class="title">{{ $p->caption ?: '-' }}</td>
                    <td class="sub">{{ $p->alt_text ?: '-' }}</td>
                    <td>{{ $p->sort_order }}</td>
                    <td class="acts"><a class="btn-line btn-sm-x" href="{{ route('admin.gallery.edit', $p) }}">Edit</a>
                        @include('admin.partials.delete-form', ['action' => route('admin.gallery.destroy', $p), 'confirm' => 'Hapus foto ini dari galeri?'])</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="hint mt-2">Urutan tampil: angka kecil lebih dulu. Ubah lewat tombol Edit.</div>
    @endif
</div>
@endsection
