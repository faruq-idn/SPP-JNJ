<!-- Select2 Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

<!-- DataTable Custom Styles -->
<style>
    /* Table styles */
    .dataTables_wrapper .btn-group {
        gap: 0.25rem;
    }
    .table td:not(:last-child) {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<style>
    /* Fixed sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 250px;
        z-index: 100;
        overflow-y: auto;
        background-color: #343a40;
        color: white;
    }

    /* Fixed navbar */
    .top-navbar {
        position: fixed;
        top: 0;
        right: 0;
        left: 250px;
        z-index: 99;
        background: white;
    }

    /* Main content padding */
    .main-content {
        margin-left: 250px;
        padding-top: 70px;
    }

    /* Enhanced Card Styles */
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .card-header {
        font-weight: 700;
        padding: 1rem 1.25rem;
        margin-bottom: 0;
        color: #4e73df;
    }

    /* Progress Bar Enhancements */
    .progress {
        height: 0.6rem;
        border-radius: 0.5rem;
    }

    .progress-bar {
        border-radius: 0.5rem;
    }

    /* Status Card Enhancements */
    .status-card {
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .status-card .title {
        font-size: 0.8rem;
        color: #858796;
        margin-bottom: 0.25rem;
    }

    .status-card .value {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    /* Badge Enhancements */
    .badge {
        font-weight: 600;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    /* Hover States */
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar {
            left: -250px;
            transition: 0.3s;
        }
        .sidebar.show {
            left: 0;
        }
        .main-content {
            margin-left: 0;
        }
        .top-navbar {
            left: 0;
        }
    }

    /* Sidebar styles */
    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.8rem 1rem;
        border-radius: 0.25rem;
        margin: 0.2rem 0;
    }

    .nav-link:hover,
    .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
    }

    #userSubmenu .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    /* Rest of your existing styles... */
    /* ... (keep all the existing styles below this point) */
</style>
