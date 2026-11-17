@extends('layouts.admin')
@section('title', 'Menu')
@section('heading', 'Menu')
@section('actions')<a href="{{ route('admin.menu-items.create') }}" class="btn btn-brand btn-sm"><i class="fas fa-plus me-1"></i>Tambah menu</a>@endsection

@section('content')
<div class="card-a">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6"><input type="search" name="q" value="{{ $filters['search'] }}" class="form-control" placeholder="Cari nama menu..."></div>
        <div class="col-md-4">
            <select name="category" class="form-select" aria-label="Kategori">
                <option value="">Semua kategori</option>
                @foreach($categories as $c)<option value="{{ $c->id }}" @selected($filters['category'] === $c->id)>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-line flex-fill" type="submit">Cari</button>@if($filters['search'] || $filters['category'])<a class="btn btn-line" href="{{ route('admin.menu-items.index') }}">Reset</a>@endif</div>
    </form>
    @if($items->isEmpty())
        <div class="empty-a"><i class="fas fa-burger"></i>Belum ada menu yang cocok.
            <div class="mt-3"><a class="btn btn-brand btn-sm" href="{{ route('admin.menu-items.create') }}">Tambah menu</a></div></div>
    @else
        <div class="tbl-wrap"><table class="tbl">
            <thead><tr><th></th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($items as $m)
                <tr>
                    <td><img class="thumb" src="{{ $m->image_url }}" alt=""></td>
                    <td><div class="title">{{ $m->name }} @if($m->is_featured)<i class="fas fa-star" style="color:var(--secondary);" title="Unggulan"></i>@endif</div><div class="sub">Urutan {{ $m->sort_order }}</div></td>
                    <td>{{ $m->category->name }}</td>
                    <td>{{ $m->price_label }}</td>
                    <td><span class="pill {{ $m->is_available ? 'published' : 'off' }}">{{ $m->is_available ? 'Tersedia' : 'Habis' }}</span></td>
                    <td class="acts">
                        <a class="btn-line btn-sm-x" href="{{ route('admin.menu-items.edit', $m) }}">Edit</a>
                        <a class="btn-line btn-sm-x" href="{{ route('menu.show', $m->slug) }}" target="_blank" rel="noopener">Lihat</a>
                        @include('admin.partials.delete-form', ['action' => route('admin.menu-items.destroy', $m), 'confirm' => 'Hapus menu "'.$m->name.'"?'])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="mt-3">{{ $items->links() }}</div>
    @endif
</div>
@endsection
