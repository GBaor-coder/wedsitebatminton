<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Panel'; ?> - JP Sport</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/websitebatminton/assets/css/admin.css">
    
    <style>
        body {
            background-color: #f5f6fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
        }
        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
            color: white;
        }
        .sidebar a i {
            width: 25px;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
        }
        .stat-card.blue { background: linear-gradient(45deg, #3498db, #2980b9); }
        .stat-card.green { background: linear-gradient(45deg, #2ecc71, #27ae60); }
        .stat-card.orange { background: linear-gradient(45deg, #f39c12, #e67e22); }
        .stat-card.red { background: linear-gradient(45deg, #e74c3c, #c0392b); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="text-center py-4 border-bottom border-secondary">
                    <h4><i class="fas fa-badmminton"></i> JP SPORT</h4>
                    <small>Admin Panel</small>
                </div>
                
                <div class="py-3">
                    <a href="/websitebatminton/admin/dashboard" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="/websitebatminton/admin/products" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'products') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-box"></i> Sản phẩm
                    </a>
                    <a href="/websitebatminton/admin/categories" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'categories') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-tags"></i> Danh mục
                    </a>
                    <a href="/websitebatminton/admin/orders" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'orders') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i> Đơn hàng
                    </a>
                    <a href="/websitebatminton/admin/users" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Người dùng
                    </a>
                    <div class="border-top border-secondary my-2"></div>
                    <a href="/websitebatminton">
                        <i class="fas fa-home"></i> Xem website
                    </a>
                    <a href="/websitebatminton/admin/logout">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Top Navbar -->
<div class="row mb-4">
                    <div class="col-md-12">
                        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm px-3">
                            <span class="navbar-brand mb-0 h1"><?php echo $title ?? 'Dashboard'; ?></span>
                            
                            <div class="navbar-nav ms-auto">
                                <span class="nav-item nav-link">
                                    <i class="fas fa-user-circle"></i> 
                                    <?php echo $admin_user['name'] ?? 'Admin'; ?>
                                </span>
                            </div>
                        </nav>
                    </div>
                </div>

<!-- Quick Access Buttons -->
<div class="mb-4">
    <div class="row g-2">
        <div class="col">
            <a href="/websitebatminton/admin/products" class="btn btn-outline-primary">
                <i class="bi bi-box me-1"></i>Sản phẩm
            </a>
        </div>
        <div class="col">
            <a href="/websitebatminton/admin/categories" class="btn btn-outline-success">
                <i class="bi bi-tags me-1"></i>Danh mục
            </a>
        </div>
        <div class="col">
            <a href="/websitebatminton/admin/orders" class="btn btn-outline-warning">
                <i class="bi bi-cart me-1"></i>Đơn hàng
            </a>
        </div>
        <div class="col">
            <a href="/websitebatminton/admin/posts" class="btn btn-outline-info">
                <i class="bi bi-file-earmark-text me-1"></i>Bài viết
            </a>
        </div>
        <div class="col">
            <a href="/websitebatminton/admin/contacts/messenger" class="btn btn-outline-secondary">
                <i class="bi bi-chat-dots me-1"></i>Tin nhắn
            </a>
        </div>
        <div class="col">
            <a href="/websitebatminton/admin/users" class="btn btn-outline-dark">
                <i class="bi bi-person me-1"></i>Người dùng
            </a>
        </div>
    </div>
</div>
                
                <!-- Flash Messages -->
                <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

