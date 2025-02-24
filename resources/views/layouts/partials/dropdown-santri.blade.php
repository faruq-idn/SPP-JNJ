@push('styles')
<style>
.dropdown-item-form {
    margin: 0;
    padding: 0;
}
.dropdown-item {
    padding: 0.75rem 1rem;
    width: 100%;
    text-align: left;
    border: none;
    background: none;
    cursor: pointer;
}
.dropdown-item:active {
    color: white;
}
.dropdown-toggle::after {
    margin-left: 1rem;
}
.santri-selector {
    min-height: 45px;
}
.dropdown-santri .dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk touch events pada dropdown items
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('touchstart', function(e) {
            e.preventDefault(); // Prevent double-firing on mobile
            this.closest('form').submit();
        });
    });
});
</script>
@endpush

<!-- Dropdown Santri Component -->
@if($santri_list->count() > 1)
<div class="card shadow-sm rounded-3 border-0 mb-3">
    <div class="card-body p-2 p-md-3">
        <div class="vstack gap-2 gap-md-3">
            <label class="fw-bold fs-6">Pilih Santri:</label>
            <div class="dropdown dropdown-santri">
                <button class="btn btn-light dropdown-toggle w-100 d-flex justify-content-between align-items-center santri-selector" 
                        type="button" 
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <span>{{ $santri->nama }} ({{ str_pad($santri->nisn, 5, '0', STR_PAD_LEFT) }})</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu w-100">
                    @foreach($santri_list as $s)
                        <li>
                            <form action="{{ route('wali.change-santri') }}" method="POST" class="dropdown-item-form">
                                @csrf
                                <input type="hidden" name="santri_id" value="{{ $s->id }}">
                                <button type="submit" class="dropdown-item {{ $santri->id == $s->id ? 'active' : '' }}">
                                    {{ $s->nama }} ({{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }})
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
