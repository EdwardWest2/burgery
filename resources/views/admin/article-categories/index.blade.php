@extends('layouts.admin')
@section('title', 'Kategori Artikel')
@section('heading', 'Kategori Artikel')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-a">
            <h2>Tambah kategori</h2>
            <form method="POST" action="{{ route('admin.article-categories.store') }}">
                @csrf
                <div class="mb-3"><label class="form-label" for="name">Nama *</label><input id="name" name="name" class="form-control" value="{{ old('name') }}" maxlength="100" required></div>
                <div class="mb-3"><label class="form-label" for="description">Deskripsi</label><textarea id="description" name="description" rows="3" class="form-control" maxlength="500">{{ old('description') }}</textarea></div>
                <button class="btn btn-brand" type="submit">Tambah kategori</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-a">
            @if($categories->isEmpty())
                <div class="empty-a"><i class="fas fa-tags"></i>Belum ada kategori. Tambahkan kategori pertama di samping.</div>
            @else
            <div class="tbl-wrap"><table class="tbl">
                <thead><tr><th>Nama</th><th>Slug</th><th>Jumlah artikel</th><th></th></tr></thead>
                <tbody>
                @foreach($categories as $c)
                    <tr>
                        <td class="title">{{ $c->name }}</td>
                        <td class="sub">{{ $c->slug }}</td>
                        <td><a href="{{ route('admin.articles.index', ['category' => $c->id]) }}">{{ $c->articles_count }} artikel</a></td>
                        <td class="acts">
                            <a class="btn-line btn-sm-x" href="{{ route('admin.article-categories.edit', $c) }}">Edit</a>
                            @include('admin.partials.delete-form', ['action' => route('admin.article-categories.destroy', $c), 'confirm' => 'Hapus kategori "'.$c->name.'"?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="hint mt-2">Kategori yang masih memiliki artikel tidak dapat dihapus.</div>
            @endif
        </div>
    </div>
</div>
@endsection
