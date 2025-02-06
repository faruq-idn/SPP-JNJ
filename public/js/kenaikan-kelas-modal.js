class KenaikanKelasModal {
    static generateDetailKenaikanKelas(santriRows) {
        const kelasGrouped = {};
        const kelas9SMP = [];
        const kelas12SMA = [];
        const tidakBisaNaik = [];
        
        santriRows.forEach((row, index) => {
            const nama = row.cells[2].textContent.trim();
            const kelasText = row.cells[3].textContent.trim();
            const [jenjang, kelas] = kelasText.split(' ');
            const id = row.querySelector('.santri-checkbox').value;
            
            // Kelas 9 SMP
            if (jenjang === 'SMP' && kelas.startsWith('9')) {
                kelas9SMP.push({ nama, id, kelas });
                return;
            }

            // Kelas 12 SMA
            if (jenjang === 'SMA' && kelas.startsWith('12')) {
                kelas12SMA.push({ nama, id, kelas });
                return;
            }
            
            // Kelas normal
            const kelasTujuan = window.kenaikanKelasService.getKelasTujuan(jenjang, kelas);
            if (!kelasTujuan) {
                tidakBisaNaik.push({ nama, kelas: `${jenjang} ${kelas}` });
                return;
            }
            
            const kelasKey = `${jenjang} ${kelas}`;
            if (!kelasGrouped[kelasKey]) {
                kelasGrouped[kelasKey] = [];
            }
            kelasGrouped[kelasKey].push({
                nama,
                kelasTujuan
            });
        });

        return { kelasGrouped, kelas9SMP, kelas12SMA, tidakBisaNaik };
    }

    static renderModal(data) {
        let detailHtml = '<ul class="list-group list-group-flush">';
        
        // Render kelas 12 SMA
        if (data.kelas12SMA.length > 0) {
            detailHtml += this.renderKelas12Section(data.kelas12SMA);
        }
        
        // Render kelas 9 SMP
        if (data.kelas9SMP.length > 0) {
            detailHtml += this.renderKelas9Section(data.kelas9SMP);
        }
        
        // Render kelas lainnya
        Object.entries(data.kelasGrouped).forEach(([kelasAsal, santri]) => {
            detailHtml += this.renderKelasSection(kelasAsal, santri);
        });
        
        // Render siswa yang tidak bisa naik kelas
        if (data.tidakBisaNaik && data.tidakBisaNaik.length > 0) {
            detailHtml += this.renderTidakBisaNaikSection(data.tidakBisaNaik);
        }
        
        detailHtml += '</ul>';
        
        if (Object.keys(data.kelasGrouped).length === 0 && 
            data.kelas9SMP.length === 0 && 
            data.kelas12SMA.length === 0) {
            detailHtml = '<div class="alert alert-warning">Tidak ada santri yang dapat diproses kenaikan kelasnya</div>';
        }
        
        return detailHtml;
    }

    static renderKelas12Section(kelas12SMA) {
        const currentYear = new Date().getFullYear();
        return `
            <li class="list-group-item bg-info text-white">
                <h6>Kelas 12 SMA - Kelulusan</h6>
                <div class="alert alert-light">
                    <strong>Perhatian!</strong>
                    <p class="mb-2">Santri kelas 12 akan diproses kelulusan:</p>
                    <div class="form-group mb-3">
                        <label for="tahunTamat">Tahun Tamat</label>
                        <select class="form-control" id="tahunTamat">
                            <option value="${currentYear}">${currentYear}</option>
                            <option value="${currentYear + 1}">${currentYear + 1}</option>
                        </select>
                    </div>
                </div>
                <div class="list-group">
                    ${kelas12SMA.map(s => `
                        <div class="list-group-item">
                            ${s.nama}
                            <input type="hidden" name="santri12[]" value="${s.id}">
                        </div>
                    `).join('')}
                </div>
            </li>
        `;
    }

    static renderKelas9Section(kelas9SMP) {
        return `
            <li class="list-group-item bg-warning">
                <h6>Kelas 9 SMP</h6>
                <div class="alert alert-info">
                    <p class="mb-2">Untuk santri kelas 9 SMP, pilih tindakan:</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="kelas9Action" id="lulus" value="lulus" checked>
                        <label class="form-check-label" for="lulus">
                            Lulus (Tidak melanjutkan di sekolah ini)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kelas9Action" id="lanjut" value="lanjut">
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
                                    value="${s.id}" id="santri9-${s.id}" checked>
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

    static renderKelasSection(kelasAsal, santri) {
        if (!santri || santri.length === 0) return '';
        
        return `
            <li class="list-group-item">
                <h6>Kelas ${kelasAsal} → Kelas ${santri[0].kelasTujuan}</h6>
                <ul>
                    ${santri.map(s => `<li>${s.nama}</li>`).join('')}
                </ul>
            </li>
        `;
    }

    static renderTidakBisaNaikSection(tidakBisaNaik) {
        return `
            <li class="list-group-item bg-warning">
                <h6>Tidak Dapat Diproses</h6>
                <div class="alert alert-warning">
                    <strong>Perhatian!</strong>
                    <p>Santri berikut tidak dapat diproses kenaikan kelasnya:</p>
                    <ul>
                        ${tidakBisaNaik.map(s => `<li>${s.nama} (${s.kelas})</li>`).join('')}
                    </ul>
                </div>
            </li>`;
    }
}

// Expose ke global scope
window.kenaikanKelasModal = KenaikanKelasModal;
