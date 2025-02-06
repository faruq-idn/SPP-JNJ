@extends('layouts.admin')

@section('title', 'Data Santri')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Data Santri
            @isset($currentKelas)
            - Kelas {{ $currentKelas['jenjang'] }} {{ $currentKelas['kelas'] }}
            @endisset
        </h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.santri.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus fa-sm"></i>
                Tambah Santri
            </a>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import fa-sm"></i>
                Import Data
            </button>
            <a href="{{ route('admin.santri.export') }}" class="btn btn-sm btn-info">
                <i class="fas fa-file-export fa-sm"></i>
                Export Data
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {!! nl2br(e(session('warning'))) !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Santri</h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-success" id="btnKenaikanKelas" style="display: none;" onclick="kenaikanKelas()">
                <i class="fas fa-level-up-alt fa-sm"></i>
                Naikan Kelas
            </button>
            <button type="button" class="btn btn-sm btn-warning" id="btnBatalKenaikan" style="display: none;" onclick="batalKenaikanKelas()">
                <i class="fas fa-undo fa-sm"></i>
                Batal Naik Kelas
            </button>
        </div>
    </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="checkAll" onclick="toggleCheckAll()">
                            </th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Wali</th>
                            <th>Status</th>
                            <th>Status SPP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($santri as $s)
                        <tr>
                            <td>
                                <input type="checkbox" class="santri-checkbox" value="{{ $s->id }}" onchange="toggleButtons()">
                            </td>
                            <td>{{ $s->nisn }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->jenjang }} {{ $s->kelas }}</td>
                            <td>{{ $s->kategori->nama ?? '-' }}</td>
                            <td>{{ $s->wali->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $s->status_color }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $s->status_spp_color }}">
                                    {{ $s->status_spp }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.santri.edit', $s->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.santri.destroy', $s->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $santri->links() }}
        </div>
    </div>
</div>

@include('admin.santri.partials.modal-import')
@include('admin.santri.partials.modal-kenaikan-kelas')
@endsection

@push('scripts')
<script>
// Add CSRF token to all ajax requests
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': token
    }
});

// Untuk request yang menggunakan dataType: 'json'
function setupAjaxRequest(data) {
    return {
        ...data,
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    };
}

function toggleCheckAll() {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    
    Array.from(checkboxes).forEach(checkbox => {
        checkbox.checked = checkAll.checked;
    });
    
    toggleButtons();
}

function toggleButtons() {
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    const btnKenaikanKelas = document.getElementById('btnKenaikanKelas');
    const btnBatalKenaikan = document.getElementById('btnBatalKenaikan');
    
    if (checkedBoxes.length > 0) {
        btnKenaikanKelas.style.display = 'inline-block';
        btnBatalKenaikan.style.display = 'inline-block';
    } else {
        btnKenaikanKelas.style.display = 'none';
        btnBatalKenaikan.style.display = 'none';
    }
}

function getKelasTujuan(jenjang, kelas) {
    const tingkat = parseInt(kelas);
    
    // Validasi format kelas (contoh: "7A", "8B", dst)
    if (isNaN(tingkat)) {
        return null;
    }
    
    const suffix = kelas.replace(tingkat, '');
    let kelasBaru = tingkat + 1;
    
    // Jika kelas 9 SMP, tetapkan null agar ditangani khusus
    if (jenjang === 'SMP' && tingkat === 9) {
        return null;
    }
    // Jika kelas 12 SMA, tidak bisa naik kelas
    else if (jenjang === 'SMA' && tingkat === 12) {
        return null;
    }
    // Naik 1 tingkat dengan suffix yang sama
    else {
        return kelasBaru + suffix;
    }
}

function kenaikanKelas() {
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    const santriRows = checkedBoxes.map(cb => cb.closest('tr'));
    
    let detailHtml = '<ul class="list-group list-group-flush">';
    const kelasGrouped = {};
    const kelas9SMP = [];
    
    santriRows.forEach(row => {
        const nama = row.cells[2].textContent;
        const [jenjang, kelas] = row.cells[3].textContent.trim().split(' ');
        
        // Pisahkan santri kelas 9 SMP
        if (jenjang === 'SMP' && kelas.startsWith('9')) {
            kelas9SMP.push({
                nama,
                id: row.querySelector('.santri-checkbox').value
            });
            return;
        }
        
        const kelasTujuan = getKelasTujuan(jenjang, kelas);
        if (!kelasTujuan) {
            alert(`Tidak dapat menaikkan kelas untuk ${nama} (${jenjang} ${kelas})`);
            return;
        }
        
        if (!kelasGrouped[`${jenjang} ${kelas}`]) {
            kelasGrouped[`${jenjang} ${kelas}`] = [];
        }
        kelasGrouped[`${jenjang} ${kelas}`].push({
            nama,
            kelasTujuan
        });
    });
    
    // Jika ada santri kelas 9 SMP
    if (kelas9SMP.length > 0) {
        detailHtml += `
            <li class="list-group-item bg-warning">
                <h6>Kelas 9 SMP</h6>
                <div class="alert alert-info">
                    <strong>Peringatan!</strong>
                    <p class="mb-2">Untuk santri kelas 9 SMP, pilih santri yang akan diproses:</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="kelas9Action" id="lulus" value="lulus" checked onchange="toggleKelas9Checkboxes()">
                        <label class="form-check-label" for="lulus">
                            Lulus (Tidak melanjutkan di sekolah ini)
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="kelas9Action" id="lanjut" value="lanjut" onchange="toggleKelas9Checkboxes()">
                        <label class="form-check-label" for="lanjut">
                            Lanjut ke kelas 10 SMA
                        </label>
                    </div>
                </div>
                <div class="list-group">
                    ${kelas9SMP.map(s => `
                        <div class="list-group-item">
                            <div class="form-check">
                                <input class="form-check-input kelas9-checkbox" type="checkbox" 
                                       value="${s.id}" id="santri9-${s.id}">
                                <label class="form-check-label" for="santri9-${s.id}">
                                    ${s.nama}
                                </label>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </li>
        `;
    }
    
    // Generate detail HTML untuk kelas lain
    Object.entries(kelasGrouped).forEach(([kelasAsal, santri]) => {
        detailHtml += `
            <li class="list-group-item">
                <h6>Kelas ${kelasAsal} → Kelas ${santri[0].kelasTujuan}</h6>
                <ul>
                    ${santri.map(s => `<li>${s.nama}</li>`).join('')}
                </ul>
            </li>
        `;
    });
    
    detailHtml += '</ul>';
    
    if (Object.keys(kelasGrouped).length === 0 && kelas9SMP.length === 0) {
        alert('Tidak ada santri yang dapat diproses kenaikan kelasnya');
        return;
    }
    
    // Tampilkan modal dengan detail
    document.getElementById('detailKenaikanKelas').innerHTML = detailHtml;
    const modal = new bootstrap.Modal(document.getElementById('modalKenaikanKelas'));
    modal.show();
}

// Handle form submit untuk kenaikan kelas
document.getElementById('formKenaikanKelas').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalKenaikanKelas'));
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    const santriRows = checkedBoxes.map(cb => cb.closest('tr'));
    
    // Pisahkan data santri reguler dan kelas 9
    const santriData = [];
    const santriKelas9 = [];
    
    santriRows.forEach(row => {
        const [jenjang, kelas] = row.cells[3].textContent.trim().split(' ');
        const id = row.querySelector('.santri-checkbox').value;
        const nama = row.cells[2].textContent;
        
        if (jenjang === 'SMP' && kelas.startsWith('9')) {
            santriKelas9.push({ id, nama, kelas });
        } else {
            const kelasTujuan = getKelasTujuan(jenjang, kelas);
            if (kelasTujuan) {
                santriData.push({ 
                    id: parseInt(id),
                    kelasTujuan,
                    jenjang,
                    status: 'aktif'
                });
            }
        }
    });
    
    // Proses santri kelas 9 berdasarkan pilihan radio dan checkbox
    if (santriKelas9.length > 0) {
        const kelas9Action = document.querySelector('input[name="kelas9Action"]:checked').value;
        const selectedSantri9 = Array.from(document.getElementsByClassName('kelas9-checkbox'))
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        // Hanya proses santri kelas 9 yang dicentang
        santriKelas9.forEach(santri => {
            if (!selectedSantri9.includes(santri.id)) return;
            
            if (kelas9Action === 'lulus') {
                santriData.push({
                    id: santri.id,
                    kelasTujuan: null,
                    jenjang: 'SMP',
                    status: 'lulus'
                });
        } else {
            const suffix = santri.kelas ? santri.kelas.replace(/[0-9]/g, '') : 'A';
            santriData.push({
                id: parseInt(santri.id),
                kelasTujuan: '10' + suffix,
                jenjang: 'SMA',
                status: 'aktif'
                });
            }
        });
    }
    
    // Validasi data sebelum dikirim
    let errors = [];
    santriData.forEach(data => {
        if (!data.id) {
            errors.push('ID santri tidak valid');
        }
        if (data.status === 'aktif' && !data.kelasTujuan) {
            errors.push('Kelas tujuan harus diisi');
        }
        if (!['SMP', 'SMA'].includes(data.jenjang)) {
            errors.push('Jenjang tidak valid');
        }
        if (!['aktif', 'lulus'].includes(data.status)) {
            errors.push('Status tidak valid');
        }
    });

    if (errors.length > 0) {
        alert('Terjadi kesalahan validasi:\n' + errors.join('\n'));
        return;
    }

    if (santriData.length === 0) {
        alert('Tidak ada santri yang dapat diproses kenaikan kelasnya');
        return;
    }
    
    // Kirim request ke server
    fetch("{{ route('admin.santri.kenaikan-kelas') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            santri_data: santriData
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        if (!error.response) {
            alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            return;
        }

        // Log request data untuk debugging
        console.log('Request Data:', {
            santri_data: santriData
        });

        error.response.text().then(text => {
            try {
                const data = JSON.parse(text);
                alert(data.message || 'Terjadi kesalahan saat memproses kenaikan kelas');
            } catch (e) {
                console.error('Response Text:', text);
                alert('Terjadi kesalahan. Silakan periksa console untuk detail.');
            }
        });
    });
    
    modal.hide();
});

function toggleKelas9Checkboxes() {
    const actionLulus = document.getElementById('lulus').checked;
    const checkboxes = document.getElementsByClassName('kelas9-checkbox');
    
    Array.from(checkboxes).forEach(checkbox => {
        if (actionLulus) {
            checkbox.checked = true;
            checkbox.disabled = true;
        } else {
            checkbox.checked = false;
            checkbox.disabled = false;
        }
    });
}

// Panggil fungsi saat modal ditampilkan untuk mengatur status awal checkbox
document.getElementById('modalKenaikanKelas').addEventListener('shown.bs.modal', function () {
    toggleKelas9Checkboxes();
});

function batalKenaikanKelas() {
    if (!confirm('PERHATIAN!\n\nAnda akan membatalkan kenaikan kelas untuk santri yang dipilih:\n- Santri akan dikembalikan ke kelas sebelumnya\n- Status santri akan dikembalikan seperti semula\n- Riwayat kenaikan kelas akan dihapus\n\nLanjutkan pembatalan kenaikan kelas?')) {
        return;
    }
    
    const checkboxes = document.getElementsByClassName('santri-checkbox');
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    const santriIds = checkedBoxes.map(cb => parseInt(cb.value));
    
    // Kirim request ke server
    fetch("{{ route('admin.santri.batal-kenaikan-kelas') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            santri_ids: santriIds
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        if (!error.response) {
            alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            return;
        }

        error.response.text().then(text => {
            try {
                const data = JSON.parse(text);
                alert(data.message || 'Terjadi kesalahan saat membatalkan kenaikan kelas');
            } catch (e) {
                console.error('Response Text:', text);
                alert('Terjadi kesalahan. Silakan periksa console untuk detail.');
            }
        });
    });
}
</script>
@endpush
