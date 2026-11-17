@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('heading', 'Pengaturan Website')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    <p class="hint mb-3">Mengosongkan kolom bertipe teks utama akan mengembalikan teks bawaan; kolom kontak/sosial yang dikosongkan akan disembunyikan dari website.</p>
    @foreach($schema as $groupKey => $group)
        <div class="card-a">
            <h2>{{ $group['label'] }}</h2>
            <div class="row g-3">
                @foreach($group['fields'] as $key => $f)
                    @php $val = old($key, $values[$key] ?? $f['default']); @endphp
                    <div class="{{ in_array($f['type'], ['textarea', 'image']) ? 'col-12' : 'col-md-6' }}">
                        <label class="form-label" for="{{ $key }}">{{ $f['label'] }}</label>
                        @if($f['type'] === 'textarea')
                            <textarea id="{{ $key }}" name="{{ $key }}" rows="{{ $key === 'opening_hours' ? 5 : 3 }}" class="form-control" maxlength="2000">{{ $val }}</textarea>
                        @elseif($f['type'] === 'image')
                            @if($val)<img id="prev-{{ $key }}" class="img-prev" src="{{ media_url($val) }}" alt="" style="max-height:80px">@else<img id="prev-{{ $key }}" class="img-prev" src="" alt="" style="display:none;max-height:80px">@endif
                            <input id="{{ $key }}" type="file" name="{{ $key }}" class="form-control" accept="image/jpeg,image/png,image/webp" data-preview="prev-{{ $key }}">
                            @if($val)<div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1"><label class="form-check-label" for="remove_logo">Hapus logo</label></div>@endif
                        @else
                            <input id="{{ $key }}" name="{{ $key }}" type="{{ $f['type'] === 'text' ? 'text' : $f['type'] }}" class="form-control" value="{{ $val }}" maxlength="255">
                        @endif
                        @isset($f['help'])<div class="hint">{{ $f['help'] }}</div>@endisset
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <button class="btn btn-brand" type="submit"><i class="fas fa-floppy-disk me-1"></i>Simpan pengaturan</button>
</form>
@endsection
