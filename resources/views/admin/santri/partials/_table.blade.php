<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Santri</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($santri as $s)
                    <tr>
                        <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->nisn }}</td>
                        <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->nama }}</td>
                        <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->jenjang }} {{ $s->kelas }}</td>
                        <td onclick="window.location='{{ route('admin.santri.show', $s->id) }}'">{{ $s->kategori->nama ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $s->status_color }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.santri.show', $s->id) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.santri.edit', ['santri' => $s->id]) }}" 
                                   class="btn btn-sm btn-primary"
                                   onclick="event.preventDefault(); window.location.href='{{ route('admin.santri.edit', ['santri' => $s->id]) }}';">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        onclick="hapusSantri({{ $s->id }})"
                                        class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
