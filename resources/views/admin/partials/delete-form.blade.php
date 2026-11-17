<form method="POST" action="{{ $action }}" data-confirm="{{ $confirm }}">
    @csrf @method('DELETE')
    <button type="submit" class="btn-danger-x"><i class="fas fa-trash"></i> {{ $label ?? 'Hapus' }}</button>
</form>
