{{-- Chart.js --}}
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>

{{-- jQuery --}}
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
{{-- Bootstrap JS --}}
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
{{-- Select2 --}}
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
{{-- SweetAlert2 --}}
<script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

{{-- Default Select2 Configuration --}}
<script>
// Pastikan jQuery loaded sebelum menjalankan script
function initializeComponents() {
    console.log('initializeComponents dipanggil.');
    if (typeof jQuery === 'undefined') {
        console.warn('jQuery belum dimuat, mencoba lagi...');
        setTimeout(initializeComponents, 100);
        return;
    }
    console.log('jQuery sudah dimuat.');

    // Select2 default config
    if ($.fn.select2) {
        console.log('Select2 terdeteksi, menginisialisasi konfigurasi default.');
        $.fn.select2.defaults.set("theme", "bootstrap-5");
        $.fn.select2.defaults.set("language", "id");
    } else {
        console.warn('Select2 tidak terdeteksi.');
    }

    // SweetAlert2 toast config
    if (typeof Swal !== 'undefined') {
        console.log('SweetAlert2 terdeteksi, menginisialisasi konfigurasi toast.');
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Show success toast if exists
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired in scripts.blade.php');
    initializeComponents();
});

// Prevent back button
window.addEventListener('pageshow', function(event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        window.location.reload();
    }
});

// Update time & date
function updateTime() {
    const now = new Date();
    const time = padZero(now.getHours()) + ':' +
                padZero(now.getMinutes()) + ':' +
                padZero(now.getSeconds());

    const timeElement = document.getElementById('currentTime');
    if (timeElement) {
        timeElement.textContent = time;
    }
}

function updateDate() {
    const now = new Date();
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const dateStr = days[now.getDay()] + ', ' +
                   now.getDate() + ' ' +
                   months[now.getMonth()] + ' ' +
                   now.getFullYear();

    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        dateElement.textContent = dateStr;
    }
}

// Initialize date time
document.addEventListener('DOMContentLoaded', function() {
    updateTime();
    updateDate();
    setInterval(updateTime, 1000);
});

// Helper function
function padZero(num) {
    return num < 10 ? '0' + num : num;
}

// Toggle sidebar (only for admin/petugas layout)
if (document.getElementById('sidebarToggle')) {
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });

    // Hide sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebarToggle');
            if (sidebar && toggle && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
}

// Logout confirmation
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: "Anda yakin ingin keluar dari sistem?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}

</script>

@if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
{{-- Admin & Petugas shared scripts --}}
<script>
    window.role = '{{ Auth::user()->role }}';
    window.isAdmin = {{ Auth::user()->role === 'admin' ? 'true' : 'false' }};
    console.log('Global variables set: role=', window.role, 'isAdmin=', window.isAdmin);
</script>
<script src="{{ asset('js/pembayaran.js') }}"></script>
@endif
<script>
(function() {
    if (window.__modalGlobalHandlerAttached) return;
    window.__modalGlobalHandlerAttached = true;

    function cleanupIfNoModal() {
        const visibleCount = document.querySelectorAll('.modal.show').length;
        if (visibleCount === 0) {
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
        }
    }

    document.addEventListener('show.bs.modal', function(event) {
        const modalElement = event.target;
        const openCount = document.querySelectorAll('.modal.show').length;
        const baseBackdrop = 1050; // Bootstrap 5 default
        const baseModal = 1055;    // Bootstrap 5 default
        const modalZ = baseModal + (10 * openCount);
        const backdropZ = baseBackdrop + (10 * openCount);
        modalElement.style.zIndex = modalZ;
        setTimeout(function() {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            const backdrop = backdrops[backdrops.length - 1];
            if (backdrop) {
                backdrop.style.zIndex = backdropZ;
            }
        }, 0);
    });

    document.addEventListener('hidden.bs.modal', function() {
        setTimeout(cleanupIfNoModal, 0);
    });

    document.addEventListener('DOMContentLoaded', function() {
        cleanupIfNoModal();
    });
})();
</script>

@if(Auth::user()->role === 'admin')
{{-- Admin specific scripts --}}
<script src="{{ asset('js/kenaikan-kelas.js') }}"></script>
<script src="{{ asset('js/sidebar-admin.js') }}"></script>
@endif
