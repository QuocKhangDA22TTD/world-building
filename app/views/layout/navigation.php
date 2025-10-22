<div class="navigation text-uppercase font-weight-bold">
    <div class="navbar navbar-expand m-0 p-0 w-100 h-100 justify-content-end">
        <ul class="navbar-nav px-3">
            <li class="nav-item">
                <a class="nav-link p-3" href="/world-building/public/">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link p-3" href="/world-building/public/?url=world">
                    <i class="fas fa-globe"></i> Thế giới
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link p-3" href="/world-building/public/?url=entity<?= isset($_SESSION['current_world_id']) ? '&world_id=' . $_SESSION['current_world_id'] : '' ?>">
                    <i class="fas fa-users"></i> Thực thể
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link p-3" href="/world-building/public/?url=relationship<?= isset($_SESSION['current_world_id']) ? '&world_id=' . $_SESSION['current_world_id'] : '' ?>">
                    <i class="fas fa-link"></i> Mối quan hệ
                </a>
            </li>
            
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                <?php
                // Kiểm tra role admin từ session
                $isAdmin = (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'super_admin']));
                ?>
                
                <!-- User đã đăng nhập -->
                <li class="nav-item dropdown">
                    <a class="nav-link p-3 dropdown-toggle" href="#" id="userDropdown" role="button" 
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                        <?php 
                        $userRole = $_SESSION['role'] ?? 'user';
                        if ($userRole === 'super_admin'): ?>
                            <span class="badge badge-danger"><i class="fas fa-crown"></i> Super Admin</span>
                        <?php elseif ($userRole === 'admin'): ?>
                            <span class="badge badge-warning"><i class="fas fa-shield-alt"></i> Admin</span>
                        <?php elseif ($userRole === 'moderator'): ?>
                            <span class="badge badge-info"><i class="fas fa-user-shield"></i> Moderator</span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <?php if ($isAdmin): ?>
                            <!-- Menu Quản trị (admin và super_admin) -->
                            <a class="dropdown-item text-warning font-weight-bold" href="/world-building/public/?url=admin">
                                <i class="fas fa-shield-alt"></i> Quản trị hệ thống
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php elseif ($userRole === 'moderator'): ?>
                            <!-- Menu Quản trị (moderator - chỉ worlds) -->
                            <a class="dropdown-item text-info font-weight-bold" href="/world-building/public/?url=admin/worlds">
                                <i class="fas fa-globe"></i> Quản lý Thế giới
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>
                        
                        <a class="dropdown-item" href="/world-building/public/?url=user/profile">
                            <i class="fas fa-user"></i> Thông tin cá nhân
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="/world-building/public/?url=auth/logout">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </li>
            <?php else: ?>
                <!-- User chưa đăng nhập -->
                <li class="nav-item">
                    <a class="nav-link p-3" href="/world-building/public/?url=auth/login">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-3 btn btn-primary text-white ml-2" href="/world-building/public/?url=auth/register">
                        <i class="fas fa-user-plus"></i> Đăng ký
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<style>
    /* Đảm bảo dropdown menu hiển thị đúng */
    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 1000;
        display: none;
        min-width: 10rem;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 0.25rem;
    }
    
    .dropdown-menu.show {
        display: block;
    }
    
    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.5rem 1.5rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        text-decoration: none;
    }
    
    .dropdown-item:hover,
    .dropdown-item:focus {
        color: #16181b;
        text-decoration: none;
        background-color: #f8f9fa;
    }
    
    .dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }
</style>

<script>
    // Đảm bảo Bootstrap dropdown được khởi tạo khi DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tất cả dropdown
        var dropdowns = document.querySelectorAll('.dropdown-toggle');
        dropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Đóng tất cả dropdown khác
                document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                    menu.classList.remove('show');
                });
                
                // Toggle dropdown hiện tại
                var menu = this.nextElementSibling;
                menu.classList.toggle('show');
            });
        });
        
        // Đóng dropdown khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
                document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                    menu.classList.remove('show');
                });
            }
        });
    });
</script>