@extends('layouts.admin')
@php $isEdit = $location->exists; @endphp
@section('title', $isEdit ? 'Edit Lokasi' : 'Tambah Lokasi')
@section('heading', $isEdit ? 'Edit Lokasi' : 'Tambah Lokasi')

@section('content')
<div class="card-a" style="max-width:720px;">
    <form method="POST" action="{{ $isEdit ? route('admin.locations.update', $location) : route('admin.locations.store') }}">
        @csrf @if($isEdit) @method('PUT') @endif
        <div class="mb-3"><label class="form-label" for="name">Nama lokasi *</label><input id="name" name="name" class="form-control" value="{{ old('name', $location->name) }}" maxlength="150" required></div>
        <div class="mb-3"><label class="form-label" for="address">Alamat *</label><textarea id="address" name="address" rows="3" class="form-control" maxlength="500" required>{{ old('address', $location->address) }}</textarea></div>
        <div class="row g-3 mb-3">
            <div class="col-md-6"><label class="form-label" for="phone">Telepon</label><input id="phone" name="phone" class="form-control" value="{{ old('phone', $location->phone) }}" maxlength="40"></div>
            <div class="col-md-6"><label class="form-label" for="sort_order">Urutan</label><input id="sort_order" name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order', $location->sort_order) }}"></div>
        </div>
        <div class="mb-3"><label class="form-label" for="opening_hours">Jam operasional</label><textarea id="opening_hours" name="opening_hours" rows="3" class="form-control" maxlength="1000">{{ old('opening_hours', $location->opening_hours) }}</textarea></div>
        <div class="mb-3"><label class="form-label" for="map_url">Tautan peta (Google Maps)</label><input id="map_url" name="map_url" type="url" class="form-control" value="{{ old('map_url', $location->map_url) }}" maxlength="500" placeholder="https://maps.google.com/..."></div>
        <div class="form-check form-switch mb-4"><input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" @checked(old('is_active', $location->is_active))><label class="form-check-label" for="is_active">Tampilkan di website</label></div>
        <button class="btn btn-brand" type="submit">Simpan</button> <a class="btn btn-line" href="{{ route('admin.locations.index') }}">Batal</a>
    </form>
</div>
@endsection
