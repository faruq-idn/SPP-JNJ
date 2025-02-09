<!-- Select2 Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

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
    /* Hover cursor hanya di halaman yang membutuhkan, bukan di semua tabel */
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

    /* Dropdown toggle icon styles */
    .dropdown-toggle-icon {
        color: rgba(255, 255, 255, 0.8) !important;
        line-height: 1;
    }

    .dropdown-toggle-icon:hover {
        color: white !important;
    }

    .dropdown-toggle-icon:focus {
        box-shadow: none !important;
    }

    .collapse:not(.show) .fas.fa-chevron-down {
        transform: rotate(-90deg);
    }

    .fas.fa-chevron-down,
    .fas.fa-angle-down {
        transition: transform 0.2s;
        width: 12px;
        text-align: center;
    }

    /* Submenu Styling */
    .submenu {
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 0.5rem;
        margin: 0.5rem;
    }

    .submenu-section {
        margin-bottom: 1rem;
    }

    .submenu-section:last-child {
        margin-bottom: 0;
    }

    .submenu-header {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .submenu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
        margin-bottom: 0.25rem;
    }

    .submenu-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .submenu-item.active {
        background: rgba(255, 255, 255, 0.15);
        color: white;
    }

    .submenu-item .badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
    }

    .btn-all-santri {
        display: block;
        text-align: center;
        padding: 0.5rem;
        background: #0d6efd;
        color: white;
        text-decoration: none;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }

    .btn-all-santri:hover {
        background: #0b5ed7;
        color: white;
    }

    /* Widget card styles */
    .widget-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .widget-card:hover {
        transform: translateY(-5px);
    }

    .notification-item {
        border-left: 4px solid #28a745;
        background-color: white;
        margin-bottom: 0.5rem;
        padding: 1rem;
        border-radius: 0.25rem;
    }

    /* Navbar styles */
    .navbar .nav-link {
        color: #333 !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .navbar .dropdown-toggle::after {
        margin-left: 0.5rem;
    }

    .navbar .user-name {
        display: inline-block;
        vertical-align: middle;
    }

    /* Date & Time styles */
    #currentTime {
        font-family: 'Roboto Mono', 'Courier New', monospace;
        font-size: 1.8rem;
        font-weight: 600;
        color: #2c3e50;
        letter-spacing: 2px;
        margin: 0;
        line-height: 1;
    }

    #currentDate {
        font-size: 0.9rem;
        color: #666;
        margin-top: 2px;
    }

    .datetime-wrapper {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-right: 1rem;
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .datetime-wrapper {
            flex-direction: column;
            gap: 0.5rem;
            margin-right: 0;
            text-align: center;
            display: none;
        }
        #currentTime {
            font-size: 1.4rem;
        }
        .navbar .dropdown-toggle::after {
            display: none;
        }
        .navbar .user-name {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .main-content {
            padding: 1rem;
        }
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .card {
            border-radius: 0.5rem;
        }
        .table {
            font-size: 0.9rem;
        }
        .sidebar {
            width: 100%;
            max-width: 280px;
        }
    }

    /* Touch targets */
    @media (hover: none) {
        .nav-link, .btn {
            padding: 0.75rem 1rem;
        }
        .dropdown-item {
            padding: 0.75rem 1.5rem;
        }
    }
</style>
