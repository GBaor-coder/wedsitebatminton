<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'JP SPORT - Cầu lông Chính hãng'; ?></title>
    <meta name="description" content="Shop cầu lông chính hãng Yonex, Victor, Lining - Vợt cầu lông, giày cầu lông, phụ kiện cầu lông">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/websitebatminton/assets/css/style.css">
</head>
<body>

<!-- Header -->
<header class="main-header">
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-3 col-md-3 col-6">
                    <a href="/websitebatminton/" class="logo">
                        <span class="logo-text">JP SPORT</span>
                    </a>
                </div>
                
                <!-- Search Bar -->
                <div class="col-lg-6 col-md-6 d-none d-md-block">
                    <form action="/websitebatminton/products" method="GET" class="search-box">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm...">
                        <button type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Right Side -->
                <div class="col-lg-3 col-md-3 col-6">
                    <div class="header-actions">
                        <!-- Hotline -->
                        <div class="hotline d-none d-lg-block">
                            <i class="bi bi-telephone"></i>
                            <span>0342826430</span>
                        </div>
                        
                        <!-- Action Icons -->
                        <div class="action-icons">
                            <a href="/websitebatminton/products" class="action-icon" title="Tra cứu">
                                <i class="bi bi-search"></i>
                            </a>
                            <a href="/websitebatminton/login" class="action-icon" title="Tài khoản">
                                <i class="bi bi-person"></i>
                            </a>
                            <a href="/websitebatminton/cart" class="action-icon" title="Giỏ hàng">
                                <i class="bi bi-cart3"></i>
                                <span class="cart-count">0</span>
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
    <nav class="main-nav navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/websitebatminton/">Trang chủ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/websitebatminton/products" id="productsDropdown" role="button" data-bs-toggle="dropdown">
                            Sản phẩm
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/websitebatminton/products?category=1">Vợt cầu lông</a></li>
                            <li><a class="dropdown-item" href="/websitebatminton/products?category=2">Giày cầu lông</a></li>
                            <li><a class="dropdown-item" href="/websitebatminton/products?category=3">Phụ kiện</a></li>
                            <li><a class="dropdown-item" href="/websitebatminton/products?category=4">Quần áo</a></li>
                            <li><a class="dropdown-item" href="/websitebatminton/products?category=5">Balo cầu lông</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/websitebatminton/news">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/websitebatminton/guide">Hướng dẫn</a>
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

