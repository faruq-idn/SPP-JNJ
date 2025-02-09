<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Default Select2 Configuration -->
<script>
$(document).ready(function() {
    // Select2 default config
    $.fn.select2.defaults.set("theme", "bootstrap-5");
    $.fn.select2.defaults.set("language", "id");

    // SweetAlert2 toast config
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

@if(Auth::user()->role === 'admin')
<!-- Admin specific scripts -->
<script src="{{ asset('js/kenaikan-kelas.js') }}"></script>
<script src="{{ asset('js/sidebar-admin.js') }}"></script>
@endif
