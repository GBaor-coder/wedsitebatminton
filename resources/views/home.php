<?php 
// Set page title
$pageTitle = 'JP SPORT - Cầu lông Chính hãng';
require_once ROOT_PATH . '/resources/views/layouts/header.php';
?>

<!-- ============================================
     HERO BANNER SECTION
============================================= -->
<section class="hero-banner">
    <div class="container-fluid p-0">
        <div class="hero-wrapper">
            <!-- Background Image -->
            <div class="hero-bg">
                <img src="/websitebatminton/assets/images/banner/banner.jpg" alt="Banner sản phẩm" class="img-fluid">
            </div>
            
            <!-- Overlay -->
            <div class="hero-overlay"></div>
            
            <!-- Content -->
            <div class="hero-content">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-7">
                            <div class="hero-text">
                                <h1 class="hero-title">JP SPORT</h1>
                                <h2 class="hero-subtitle">Cầu lông Chính hãng</h2>
                                <p class="hero-desc">Chuyên cung cấp vợt cầu lông, giày cầu lông và phụ kiện chính hãng từ các thương hiệu Yonex, Victor, Lining</p>
                                <div class="hero-buttons">
                                    <a href="/websitebatminton/products" class="btn btn-hero-primary">
                                        <i class="bi bi-bag"></i> Mua sắm ngay
                                    </a>
                                    <a href="/websitebatminton/about" class="btn btn-hero-outline">
                                        Tìm hiểu thêm
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-5 d-none d-md-block">
                            <div class="hero-product">
                                <img src="/websitebatminton/assets/images/banner/banner1.webp" alt="Sản phẩm nổi bật" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     POLICY SECTION - 4 Icons
============================================= -->
<section class="policy-section">
    <div class="container">
        <div class="row g-3">
            <!-- Policy 1: Vận chuyển toàn quốc -->
            <div class="col-lg-3 col-md-6">
                <div class="policy-box">
                    <div class="policy-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="policy-content">
                        <h4>Vận chuyển toàn quốc</h4>
                        <p>Miễn phí đơn hàng > 500K</p>
                    </div>
                </div>
            </div>
            
            <!-- Policy 2: Bảo đảm chất lượng -->
            <div class="col-lg-3 col-md-6">
                <div class="policy-box">
                    <div class="policy-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="policy-content">
                        <h4>Bảo đảm chất lượng</h4>
                        <p>100% hàng chính hãng</p>
                    </div>
                </div>
            </div>
            
            <!-- Policy 3: Thanh toán nhiều phương thức -->
            <div class="col-lg-3 col-md-6">
                <div class="policy-box">
                    <div class="policy-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="policy-content">
                        <h4>Thanh toán linh hoạt</h4>
                        <p>Tiền mặt, chuyển khoản, QR</p>
                    </div>
                </div>
            </div>
            
            <!-- Policy 4: Đổi trả nếu lỗi -->
            <div class="col-lg-3 col-md-6">
                <div class="policy-box">
                    <div class="policy-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="policy-content">
                        <h4>Đổi trả dễ dàng</h4>
                        <p>7 ngày đổi trả miễn phí</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     HÀNG SALE SECTION
============================================= -->
<section class="sale-section">
    <div class="container">
        <div class="sale-wrapper">
            <!-- Section Header -->
            <div class="sale-header">
                <h2 class="sale-title">HÀNG SALE</h2>
                <a href="/websitebatminton/products" class="btn btn-sale-viewall">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <!-- Products Grid -->
            <div class="row g-4">
                <?php if (!empty($hotProducts)): ?>
                    <?php foreach ($hotProducts as $product): ?>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="product-card">
                            <!-- Badges -->
                            <div class="product-badges">
                                <?php if (isset($product['quantity']) && $product['quantity'] == 0): ?>
                                <span class="badge badge-outofstock">Hết hàng</span>
                                <?php endif; ?>
                                <?php if (isset($product['featured']) && $product['featured']): ?>
                                <span class="badge badge-hot">Hot</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Product Image -->
                            <div class="product-image">
                                <img src="/websitebatminton/assets/images/product.jpg" alt="<?php echo htmlspecialchars($product['name'] ?? 'Sản phẩm'); ?>">
                            </div>
                            
                            <!-- Product Info -->
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name'] ?? 'Tên sản phẩm'); ?></h3>
                                <div class="product-price">
                                    <?php 
                                    $price = $product['price'] ?? 0;
                                    $salePrice = $product['sale_price'] ?? null;
                                    if ($salePrice && $salePrice < $price): 
                                    ?>
                                        <span class="price-old"><?php echo number_format($price, 0, ',', '.'); ?> đ</span>
                                        <span class="price-new"><?php echo number_format($salePrice, 0, ',', '.'); ?> đ</span>
                                    <?php else: ?>
                                        <span class="price-new"><?php echo number_format($price, 0, ',', '.'); ?> đ</span>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-add-cart" <?php echo (isset($product['quantity']) && $product['quantity'] == 0) ? 'disabled' : ''; ?>>
                                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-box-seam display-1 text-white-50"></i>
                        <p class="text-white">Chưa có sản phẩm nào</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Slider Dots -->
            <div class="sale-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     DANH MỤC CẦU LÔNG - 8 Categories
============================================= -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">DANH MỤC CẦU LÔNG</h2>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $index => $category): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="/websitebatminton/products?category=<?php echo $category['id']; ?>" class="category-card">
                        <!-- Diagonal Label -->
                        <div class="category-label">
                            <span>NEW</span>
                        </div>
                        
                        <!-- Category Icon -->
                        <div class="category-icon">
                            <i class="bi bi-collection"></i>
                        </div>
                        
                        <!-- Category Name -->
                        <h3 class="category-name"><?php echo htmlspecialchars($category['name']); ?></h3>
                        
                        <!-- Category Description -->
                        <p class="category-desc"><?php echo htmlspecialchars($category['description'] ?? 'Xem chi tiết'); ?></p>
                        
                        <!-- View More -->
                        <span class="category-view">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <p class="text-muted">Chưa có danh mục nào</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============================================
     TIN TỨC MỚI SECTION
============================================= -->
<section class="news-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">TIN TỨC MỚI</h2>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($latestPosts)): ?>
                <?php foreach ($latestPosts as $post): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        <!-- News Image -->
                        <div class="news-image">
                            <?php if (!empty($post['image'])): ?>
                            <img src="/websitebatminton/storage/uploads/<?php echo $post['image']; ?>" alt="<?php echo htmlspecialchars($post['title'] ?? 'Tin tức'); ?>">
                            <?php else: ?>
                            <img src="/websitebatminton/assets/images/news.jpg" alt="News">
                            <?php endif; ?>
                            <div class="news-category">Tin tức</div>
                        </div>
                        
                        <!-- News Content -->
                        <div class="news-content">
                            <div class="news-meta">
                                <span><i class="bi bi-calendar3"></i> <?php echo isset($post['created_at']) ? date('d/m/Y', strtotime($post['created_at'])) : date('d/m/Y'); ?></span>
                                <span><i class="bi bi-eye"></i> <?php echo $post['views'] ?? 0; ?> lượt xem</span>
                            </div>
                            <h3 class="news-title"><?php echo htmlspecialchars($post['title'] ?? 'Tiêu đề tin tức'); ?></h3>
                            <p class="news-excerpt"><?php echo htmlspecialchars($post['excerpt'] ?? substr($post['content'] ?? '', 0, 100)); ?>...</p>
                            <a href="/websitebatminton/news?slug=<?php echo $post['slug'] ?? ''; ?>" class="news-link">
                                Xem chi tiết <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-newspaper display-1 text-muted"></i>
                    <p class="text-muted">Chưa có tin tức nào</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============================================
     CUSTOM CSS STYLES
============================================= -->
<style>
/* CSS Variables */
:root {
    --main-color: #1C42F3;
    --main-dark: #1635c9;
    --main-light: #4a68f6;
}

/* Hero Banner Styles */
.hero-banner {
    position: relative;
    overflow: hidden;
}

.hero-wrapper {
    position: relative;
    min-height: 600px;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.hero-bg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0) 100%);
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 100px 0;
}

.hero-text {
    color: white;
}

.hero-title {
    font-size: 56px;
    font-weight: 800;
    margin-bottom: 10px;
    letter-spacing: 2px;
}

.hero-subtitle {
    font-size: 28px;
    font-weight: 500;
    margin-bottom: 20px;
    opacity: 0.9;
}

.hero-desc {
    font-size: 16px;
    margin-bottom: 30px;
    opacity: 0.85;
    max-width: 500px;
}

.hero-buttons {
    display: flex;
    gap: 15px;
}

.btn-hero-primary {
    background: var(--main-color);
    color: white;
    padding: 14px 32px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
}

.btn-hero-primary:hover {
    background: var(--main-dark);
    color: white;
    transform: translateY(-2px);
}

.btn-hero-outline {
    background: transparent;
    color: white;
    padding: 14px 32px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    border: 2px solid white;
}

.btn-hero-outline:hover {
    background: white;
    color: var(--main-color);
}

.hero-product {
    text-align: center;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

/* Policy Section */
.policy-section {
    padding: 40px 0;
    background: #fff;
    margin-top: -1px;
}

.policy-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border: 2px solid rgba(28, 66, 243, 0.2);
    border-radius: 12px;
    transition: all 0.3s;
    cursor: pointer;
}

.policy-box:hover {
    background: var(--main-color);
    border-color: var(--main-color);
    transform: translateY(-3px);
}

.policy-box:hover .policy-icon,
.policy-box:hover .policy-content h4,
.policy-box:hover .policy-content p {
    color: white;
}

.policy-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: var(--main-color);
    transition: all 0.3s;
}

.policy-content h4 {
    font-size: 15px;
    font-weight: 700;
    margin: 0 0 4px;
    color: #333;
    transition: all 0.3s;
}

.policy-content p {
    font-size: 13px;
    margin: 0;
    color: #666;
    transition: all 0.3s;
}

/* Sale Section */
.sale-section {
    padding: 50px 0;
    background: linear-gradient(135deg, #1C42F3, #1635c9);
    border-radius: 20px;
    margin: 40px 0;
}

.sale-wrapper {
    padding: 0 20px;
}

.sale-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.sale-title {
    font-size: 32px;
    font-weight: 800;
    color: white;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 0;
}

.btn-sale-viewall {
    background: white;
    color: var(--main-color);
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
}

.btn-sale-viewall:hover {
    background: var(--main-color);
    color: white;
}

/* Product Card */
.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.product-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.badge-outofstock {
    background: #dc3545;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.badge-hot {
    background: #ffc107;
    color: #333;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.product-image {
    padding: 20px;
    text-align: center;
    background: #f8f9fa;
}

.product-image img {
    max-height: 180px;
    object-fit: contain;
}

.product-info {
    padding: 20px;
}

.product-name {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 42px;
}

.product-price {
    margin-bottom: 15px;
}

.price-old {
    color: #999;
    text-decoration: line-through;
    font-size: 13px;
    margin-right: 8px;
}

.price-new {
    color: #dc3545;
    font-size: 18px;
    font-weight: 700;
}

.btn-add-cart {
    width: 100%;
    background: var(--main-color);
    color: white;
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
}

.btn-add-cart:hover:not(:disabled) {
    background: var(--main-dark);
}

.btn-add-cart:disabled {
    background: #ccc;
    cursor: not-allowed;
}

/* Sale Dots */
.sale-dots {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 30px;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    cursor: pointer;
    transition: all 0.3s;
}

.dot.active {
    background: white;
    transform: scale(1.2);
}

/* Categories Section */
.categories-section {
    padding: 60px 0;
}

.section-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-title {
    font-size: 28px;
    font-weight: 800;
    color: #333;
    position: relative;
    display: inline-block;
    padding-bottom: 15px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: var(--main-color);
    border-radius: 2px;
}

.category-card {
    position: relative;
    background: white;
    border: 2px solid var(--main-color);
    border-radius: 20px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s;
    overflow: hidden;
    display: block;
    text-decoration: none;
}

.category-card:hover {
    background: var(--main-color);
    transform: scale(1.05);
    box-shadow: 0 20px 40px rgba(28, 66, 243, 0.3);
}

.category-card:hover .category-icon,
.category-card:hover .category-name,
.category-card:hover .category-desc {
    color: white;
}

.category-card:hover .category-label span {
    background: white;
    color: var(--main-color);
}

.category-label {
    position: absolute;
    top: 0;
    right: 0;
}

.category-label span {
    display: block;
    background: var(--main-color);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 0 15px 0 15px;
    transition: all 0.3s;
}

.category-icon {
    font-size: 48px;
    color: var(--main-color);
    margin-bottom: 15px;
    transition: all 0.3s;
}

.category-name {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
    transition: all 0.3s;
}

.category-desc {
    font-size: 13px;
    color: #666;
    margin-bottom: 0;
    transition: all 0.3s;
}

.category-view {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    color: var(--main-color);
    opacity: 0;
    transition: all 0.3s;
}

.category-card:hover .category-view {
    opacity: 1;
    color: white;
}

/* News Section */
.news-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.news-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s;
    height: 100%;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.news-card:hover .news-image img {
    transform: scale(1.1);
}

.news-image {
    position: relative;
    overflow: hidden;
    height: 220px;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.5s;
}

.news-category {
    position: absolute;
    top: 15px;
    left: 15px;
    background: var(--main-color);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.news-content {
    padding: 25px;
}

.news-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 12px;
    font-size: 12px;
    color: #666;
}

.news-meta i {
    margin-right: 5px;
}

.news-title {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-excerpt {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-link {
    color: var(--main-color);
    font-weight: 600;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s;
}

.news-link:hover {
    color: var(--main-dark);
}

.news-link i {
    margin-left: 5px;
    transition: transform 0.3s;
}

.news-link:hover i {
    transform: translateX(5px);
}

/* Responsive Styles */
@media (max-width: 991px) {
    .hero-title {
        font-size: 42px;
    }
    
    .hero-subtitle {
        font-size: 22px;
    }
    
    .hero-content {
        padding: 60px 0;
    }
}

@media (max-width: 767px) {
    .hero-wrapper {
        min-height: auto;
    }
    
    .hero-bg img {
        height: 400px;
    }
    
    .hero-title {
        font-size: 32px;
    }
    
    .hero-subtitle {
        font-size: 18px;
    }
    
    .hero-desc {
        font-size: 14px;
    }
    
    .hero-buttons {
        flex-direction: column;
    }
    
    .btn-hero-primary,
    .btn-hero-outline {
        width: 100%;
        text-align: center;
    }
    
    .sale-title {
        font-size: 24px;
    }
    
    .sale-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .policy-box {
        padding: 15px;
    }
    
    .policy-icon {
        width: 40px;
        height: 40px;
        font-size: 22px;
    }
    
    .policy-content h4 {
        font-size: 13px;
    }
    
    .policy-content p {
        font-size: 11px;
    }
}

@media (max-width: 480px) {
    .hero-bg img {
        height: 350px;
    }
    
    .hero-title {
        font-size: 28px;
    }
    
    .section-title {
        font-size: 22px;
    }
}
</style>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>

