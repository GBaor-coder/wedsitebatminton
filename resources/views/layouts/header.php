<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'JP SPORT - Cầu lông Chính hãng'; ?></title>
    <meta name="description" content="Shop cầu lông chính hãng Yonex, Victor, Lining - Vợt cầu lông, giày cầu lông, phụ kiện cầu lông">
    <link rel="shortcut icon" href="/websitebatminton/assets/images/favicon/JPfavicon.png" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/websitebatminton/assets/css/style.css">
    <link rel="stylesheet" href="/websitebatminton/assets/css/products.css">
</head>
<body>

<!-- Header -->
<header class="main-header">
    <div>
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-md-3 col-6">
                    <a href="/websitebatminton/" class="jp-logo">
<img src="/websitebatminton/assets/images/logo/JPTachnen.png" alt="JP SPORT Logo" class="logo-jptachnenpng img-fluid w-75">
                    </a>
                </div>
                
                <!-- Search Bar -->
                <div class="col-lg-4 col-md-5 col-12">
                    <form action="/websitebatminton/products" method="GET" class="search-box">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm...">
                        <button type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Right Side -->
                <div class="col-lg-6 col-md-4 col-6">
                    <div class="header-actions d-flex align-items-center justify-content-end h-100">
                        <!-- Hotline -->
                        <div class="hotline d-none d-lg-flex align-items-center me-4">
                            <i class="bi bi-telephone me-2 text-primary fs-5"></i>
                            <span class="text-uppercase fw-bold me-1 text-dark">Hotline:</span>
                            <div class="hotline-numbers fw-bold custom-blue-text">
                            <span class="fw-bold">0342826430 | 0961624535 | 0898680923</span>
                            </div>
                        </div>
                        
                        <!-- Action Icons -->
                        <div class="action-icons d-flex gap-3 text-start">
                            <!-- Tra cứu đơn hàng -->
                            <div class="action-item d-flex flex-column align-items-center position-relative">
                                <div class="dropdown w-100 h-100 d-flex flex-column align-items-center">
                                    <a href="#" class="action-icon mb-1 p-2 rounded-circle border d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false" title="Tra cứu đơn hàng" style="width: 40px; height: 40px;">
                                        <i class="bi bi-search"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end p-3 shadow border-0" style="min-width: 320px; border-radius: 16px;">
                                        <h6 class="fw-bold mb-3 text-primary">
                                            <i class="bi bi-box-seam me-2"></i>Tra cứu đơn hàng
                                        </h6>
                                        <form action="/websitebatminton/tra-cuu-don-hang" method="GET">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted">Mã đơn hàng</label>
                                                <input type="text" name="order_number" class="form-control form-control-sm" placeholder="VD: ORD-20260101-ABC123" style="border-radius: 10px;">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted">Hoặc Số điện thoại</label>
                                                <input type="text" name="phone" class="form-control form-control-sm" placeholder="VD: 0342826430" style="border-radius: 10px;">
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 10px;">
                                                <i class="bi bi-search me-1"></i>Tra cứu
                                            </button>
                                        </form>
                                    </div>
                                    <small class="action-label fw-medium text-muted small-text text-nowrap">Tra cứu</small>
                                </div>
                            </div>

                            <!-- User Account -->
                            <div class="action-item d-flex flex-column align-items-center position-relative">
                                <div class="dropdown w-100 h-100 d-flex flex-column align-items-center">
                                    <a href="#" class="action-icon mb-1 p-2 rounded-circle border d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-circle"></i>
                                    </a>
                                    <?php $session = new \Session(); ?>
                                    <?php if ($session->isLoggedIn()): ?>
                                        <?php
                                            $profileUrl = '/websitebatminton/thanh-vien';
                                            $profileLabel = 'Tài khoản';
                                            $isAdmin = false;
                                            if (($session->user()['role'] ?? 'customer') === 'admin') {
                                                $profileUrl = '/websitebatminton/admin';
                                                $profileLabel = 'Admin';
                                                $isAdmin = true;
                                            }
                                        ?>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="<?= $profileUrl ?>"><?= $profileLabel ?></a></li>
                                            <?php if ($isAdmin): ?>
                                                <li><a class="dropdown-item" href="/websitebatminton/admin/dashboard">Dashboard</a></li>
                                            <?php endif; ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="/websitebatminton/logout">Đăng xuất</a></li>
                                        </ul>
                                    <?php else: ?>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="/websitebatminton/login">Đăng nhập</a></li>
                                            <li><a class="dropdown-item" href="/websitebatminton/register">Đăng ký</a></li>
                                        </ul>
                                    <?php endif; ?>
                                    <small class="action-label fw-medium text-muted small-text text-nowrap">Tài khoản</small>
                                </div>
                            </div>

                            <!-- Cart -->
                            <a href="/websitebatminton/cart" class="action-item d-flex flex-column align-items-center text-decoration-none position-relative" title="Giỏ hàng">
                                <div class="action-icon rounded-circle border p-2 d-flex align-items-center justify-content-center mb-1 position-relative" style="width: 40px; height: 40px;">
                                    <i class="bi bi-cart3"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= cartItemCount() ?></span>
                                </div>
                                <small class="action-label fw-medium text-muted small-text text-nowrap">Giỏ hàng</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Search -->
    <div class="mobile-search d-md-none px-3 pb-3">
        <form action="/websitebatminton/products" method="GET" class="search-box">
            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm...">
            <button type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    
    <!-- Main Navigation -->
    <nav class="main-nav navbar navbar-expand-sm mx-auto" style="height: 50px;">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav justify-content-center text-center">
                    <li class="nav-item">
                        <a class="nav-link text-center" href="/websitebatminton/">Trang chủ</a>
                    </li>
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle" href="/websitebatminton/products" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Sản phẩm
                        </a>
                        <div class="dropdown-menu w-100 mt-0 p-4 border-0 shadow">
                            <div class="container">
                                <div class="row">

                                    <?php if (!empty($menu)): ?>
                                        <?php foreach ($menu as $category): ?>
                                            <div class="col-md-2 mb-3">
                                                
                                                <!-- Tên danh mục -->
                                                <h6 class="fw-bold text-uppercase">
                                                    <?= htmlspecialchars($category['name']) ?>
                                                </h6>
                                                <hr>

                                                <!-- Danh sách brand -->
                                                <?php if (!empty($category['brands'])): ?>
                                                    <?php foreach ($category['brands'] as $brand): ?>
                                                        <a class="dropdown-item px-0"
                                                        href="/websitebatminton/products?category=<?= $category['id'] ?>&brand=<?= $brand['id'] ?>">
                                                            <?= htmlspecialchars($brand['name']) ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                <!-- Xem thêm -->
                                                <a class="text-danger small"
                                                href="/websitebatminton/products?category=<?= $category['id'] ?>">
                                                    Xem thêm
                                                </a>

                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/websitebatminton/news">Tin tức</a>
                    </li>
                   <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/websitebatminton/guide" id="guideDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hướng dẫn
                        </a>
                        <div class="dropdown-menu mt-0 p-3 border-0 shadow">
                            <?php if (!empty($guideMenu)): ?>
                                <?php foreach ($guideMenu as $category): ?>
                                    <a class="dropdown-item px-0 py-1 fw-bold text-primary"
                                       href="/websitebatminton/guide?category=<?= $category['id'] ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </a>
                                    <hr class="my-2">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">Chưa có dữ liệu hướng dẫn</span>
                            <?php endif; ?>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/websitebatminton/about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/websitebatminton/contact">Liên hệ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

