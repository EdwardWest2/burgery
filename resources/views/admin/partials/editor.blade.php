{{-- Editor rich-text. Variabel: $name, $value, $id --}}
<div class="rte" data-rte data-upload-url="{{ route('admin.editor.upload') }}">
    <div class="rte-bar" role="toolbar" aria-label="Format teks">
        <button type="button" data-cmd="p" title="Paragraf">P</button>
        <button type="button" data-cmd="h2" title="Judul bagian"><strong>H2</strong></button>
        <button type="button" data-cmd="h3" title="Sub judul"><strong>H3</strong></button>
        <span class="sep"></span>
        <button type="button" data-cmd="bold" title="Tebal (Ctrl+B)"><i class="fas fa-bold"></i></button>
        <button type="button" data-cmd="italic" title="Miring (Ctrl+I)"><i class="fas fa-italic"></i></button>
        <button type="button" data-cmd="underline" title="Garis bawah"><i class="fas fa-underline"></i></button>
        <span class="sep"></span>
        <button type="button" data-cmd="insertUnorderedList" title="Daftar poin"><i class="fas fa-list-ul"></i></button>
        <button type="button" data-cmd="insertOrderedList" title="Daftar nomor"><i class="fas fa-list-ol"></i></button>
        <button type="button" data-cmd="quote" title="Kutipan"><i class="fas fa-quote-left"></i></button>
        <span class="sep"></span>
        <button type="button" data-cmd="link" title="Sisipkan tautan"><i class="fas fa-link"></i></button>
        <button type="button" data-cmd="unlink" title="Hapus tautan"><i class="fas fa-link-slash"></i></button>
        <button type="button" data-cmd="image" title="Sisipkan gambar"><i class="fas fa-image"></i></button>
        <span class="sep"></span>
        <button type="button" data-cmd="undo" title="Urungkan"><i class="fas fa-rotate-left"></i></button>
        <button type="button" data-cmd="redo" title="Ulangi"><i class="fas fa-rotate-right"></i></button>
        <button type="button" data-cmd="clear" title="Hapus format"><i class="fas fa-eraser"></i></button>
        <input type="file" accept="image/jpeg,image/png,image/webp" hidden>
    </div>
    <div class="rte-area" contenteditable="true" role="textbox" aria-multiline="true" aria-label="Isi" data-placeholder="Mulai menulis di sini..."></div>
    <textarea name="{{ $name }}" id="{{ $id ?? $name }}" hidden>{{ $value }}</textarea>
    <div class="rte-status">Gambar: JPG, PNG, atau WebP, maks. 3 MB. HTML dibersihkan otomatis saat disimpan.</div>
</div>
