
<nav class="navbar navbar-expand-lg navbar-light bg-white top-navbar">
    <div class="container-fluid">
        
        <button class="navbar-toggler btn btn-sm" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        
        <div class="datetime-wrapper flex-grow-1 text-center">
            <div id="currentTime" class="h5 mb-0 fw-medium text-primary"></div>
            <div id="currentDate" class="small text-muted fw-medium"></div>
        </div>

        
        <div class="nav-item dropdown ms-auto">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" 
               href="#" 
               id="navbarDropdown" 
               role="button" 
               data-bs-toggle="dropdown" 
               aria-expanded="false">
                <i class="fas fa-user-circle fs-5 fw-medium text-primary"></i>
                <span class="user-name"><?php echo e(Auth::user()->name); ?></span>
            </a>
            <ul class="dropdown-menu shadow-sm border-0">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                        <i class="fas fa-user-cog"></i> Profil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>
                    <button type="button" 
                            class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger"
                            onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\shared\navbar\_navbar.blade.php ENDPATH**/ ?>