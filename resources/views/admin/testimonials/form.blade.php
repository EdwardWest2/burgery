@extends('layouts.admin')
@php $isEdit = $testimonial->exists; @endphp
@section('title', $isEdit ? 'Edit Testimoni' : 'Tambah Testimoni')
@section('heading', $isEdit ? 'Edit Testimoni' : 'Tambah Testimoni')

@section('content')
<div class="card-a" style="max-width:640px;">
    <form method="POST" action="{{ $isEdit ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
        @csrf @if($isEdit) @method('PUT') @endif
        <div class="mb-3"><label class="form-label" for="name">Nama *</label><input id="name" name="name" class="form-control" value="{{ old('name', $testimonial->name) }}" maxlength="100" required></div>
        <div class="mb-3"><label class="form-label" for="role">Keterangan (mis. Pelanggan setia)</label><input id="role" name="role" class="form-control" value="{{ old('role', $testimonial->role) }}" maxlength="100"></div>
        <div class="mb-3"><label class="form-label" for="content">Isi ulasan *</label><textarea id="content" name="content" rows="4" class="form-control" maxlength="600" required>{{ old('content', $testimonial->content) }}</textarea></div>
        <div class="mb-3"><label class="form-label" for="sort_order">Urutan</label><input id="sort_order" name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order', $testimonial->sort_order) }}"></div>
        <div class="form-check form-switch mb-4"><input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" @checked(old('is_active', $testimonial->is_active))><label class="form-check-label" for="is_active">Tampilkan di website</label></div>
        <button class="btn btn-brand" type="submit">Simpan</button> <a class="btn btn-line" href="{{ route('admin.testimonials.index') }}">Batal</a>
    </form>
</div>
@endsection
