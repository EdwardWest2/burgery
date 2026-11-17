@extends('layouts.admin')
@section('title', 'Halaman')
@section('heading', 'Halaman')

@section('content')
<div class="card-a">
    <p class="hint mb-3">Halaman statis. Informasi kontak, jam buka, dan media sosial diatur di <a href="{{ route('admin.settings.edit') }}">Pengaturan</a>.</p>
    <div class="tbl-wrap"><table class="tbl">
        <thead><tr><th>Judul</th><th>Alamat</th><th>Diperbarui</th><th></th></tr></thead>
        <tbody>
        @foreach($pages as $p)
            <tr>
                <td class="title">{{ $p->title }}</td>
                <td class="sub">/{{ $p->slug }}</td>
                <td class="sub">{{ $p->updated_at->translatedFormat('j M Y H:i') }}</td>
                <td class="acts"><a class="btn-line btn-sm-x" href="{{ route('admin.pages.edit', $p) }}">Edit</a>
                    <a class="btn-line btn-sm-x" href="{{ url($p->slug) }}" target="_blank" rel="noopener">Lihat</a></td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
</div>
@endsection
