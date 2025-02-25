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
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeComponents, 100);
        return;
    }

    // Select2 default config
    if ($.fn.select2) {
        $.fn.select2.defaults.set("theme", "bootstrap-5");
        $.fn.select2.defaults.set("language", "id");
    }

    // SweetAlert2 toast config
    if (typeof Swal !== 'undefined') {
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

// Toggle sidebar
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('show');
});

// Hide sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.getElementById('sidebarToggle');
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    }
});

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
</script>
<script src="{{ asset('js/pembayaran.js') }}"></script>
@endif

@if(Auth::user()->role === 'admin')
{{-- Admin specific scripts --}}
<script src="{{ asset('js/kenaikan-kelas.js') }}"></script>
<script src="{{ asset('js/sidebar-admin.js') }}"></script>
@endif
