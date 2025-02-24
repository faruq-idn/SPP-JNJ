<!-- Modal Pilih Santri -->
<div class="modal" id="pilihSantriModal" tabindex="-1" role="dialog" aria-labelledby="pilihSantriModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pilihSantriModalLabel">Pilih Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($santri_list as $s)
                        <form action="{{ route('wali.change-santri') }}" method="POST">
                            @csrf
                            <input type="hidden" name="santri_id" value="{{ $s->id }}">
                            <button type="submit"
                                class="list-group-item list-group-item-action border-0 {{ $santri->id == $s->id ? 'active fw-bold' : '' }}"
                                style="padding: 1rem;"
                                aria-label="Pilih {{ $s->nama }}"
                                {{ $santri->id == $s->id ? 'aria-current="true"' : '' }}>
                                <div class="d-flex w-100 justify-content-between align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span>{{ $s->nama }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted">NIS: {{ str_pad($s->nisn, 5, '0', STR_PAD_LEFT) }}</small>
                                        @if($santri->id == $s->id)
                                            <i class="fas fa-check text-success ms-2"></i>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tombol Pilih Santri -->
@if($santri_list->count() > 1)
<div class="card shadow-sm rounded-3 border-0 mb-3">
    <div class="card-body p-2 p-md-3">
        <div class="vstack gap-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="fw-bold fs-6 mb-0">Santri Aktif:</label>
                <small class="text-muted"><i class="fas fa-info-circle"></i> Ketuk untuk mengganti santri</small>
            </div>
            <button type="button"
                    class="btn btn-light w-100 d-flex justify-content-between align-items-center py-2"
                    style="min-height: 45px"
                    data-bs-toggle="modal"
                    data-bs-target="#pilihSantriModal"
                    aria-expanded="false"
                    aria-controls="pilihSantriModal"
                    aria-label="Pilih santri aktif">
                <div class="d-flex align-items-center gap-2">
                    <div class="vstack gap-0 text-start">
                        <span class="fw-medium">{{ $santri->nama }}</span>
                        <small class="text-muted">NIS: {{ str_pad($santri->nisn, 5, '0', STR_PAD_LEFT) }}</small>
                    </div>
                </div>
                <i class="fas fa-exchange-alt text-primary"></i>
            </button>
        </div>
    </div>
</div>
@endif
