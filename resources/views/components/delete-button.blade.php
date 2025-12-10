@props(['action', 'message' => 'Bu kaydı silmek istediğinize emin misiniz?'])

<form action="{{ $action }}" method="POST" class="delete-form d-inline-block" data-message="{{ $message }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2" title="Sil">
        <i class="fa-solid fa-trash"></i>
        <span class="d-none d-md-inline">Sil</span>
    </button>
</form>
