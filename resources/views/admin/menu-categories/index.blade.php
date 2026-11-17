@extends('layouts.admin')
@section('title', 'Kategori Menu')
@section('heading', 'Kategori Menu')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-a">
            <h2>Tambah kategori</h2>
            <form method="POST" action="{{ route('admin.menu-categories.store') }}">
                @csrf
                <div class="mb-3"><label class="form-label" for="name">Nama *</label><input id="name" name="name" class="form-control" value="{{ old('name') }}" maxlength="100" required></div>
                <div class="mb-3"><label class="form-label" for="sort_order">Urutan</label><input id="sort_order" name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order', 0) }}"><div class="hint">Angka kecil tampil lebih dulu.</div></div>
                <button class="btn btn-brand" type="submit">Tambah kategori</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-a">
            @if($categories->isEmpty())
                <div class="empty-a"><i class="fas fa-list"></i>Belum ada kategori menu.</div>
            @else
            <div class="tbl-wrap"><table class="tbl">
                <thead><tr><th>Urutan</th><th>Nama</th><th>Jumlah menu</th><th></th></tr></thead>
                <tbody>
                @foreach($categories as $c)
                    <tr>
                        <td>{{ $c->sort_order }}</td>
                        <td class="title">{{ $c->name }}<div class="sub">{{ $c->slug }}</div></td>
                        <td><a href="{{ route('admin.menu-items.index', ['category' => $c->id]) }}">{{ $c->items_count }} menu</a></td>
                        <td class="acts">
                            <a class="btn-line btn-sm-x" href="{{ route('admin.menu-categories.edit', $c) }}">Edit</a>
                            @include('admin.partials.delete-form', ['action' => route('admin.menu-categories.destroy', $c), 'confirm' => 'Hapus kategori "'.$c->name.'"?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="hint mt-2">Kategori yang masih berisi menu tidak dapat dihapus.</div>
            @endif
        </div>
    </div>
</div>
@endsection
