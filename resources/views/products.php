<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<div class="container-fluid py-5">

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 col-md-4 products-sidebar">
            <div class="sticky-top">
                <!-- Category Filter -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Danh mục</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li càlass="list-group-item">
                                <a href="?<?php if(isset($search) && $search) echo 'search=' . urlencode($search) . '&'; ?>page=1" 
                                   class="d-flex justify-content-between align-items-center text-decoration-none <?php if (!isset($categoryId)) echo 'fw-bold text-primary'; ?>">
                                    <span>Tất cả</span>
                                    <span class="badge bg-primary rounded-pill"><?php echo count($products); ?></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $category): ?>
                            <li class="list-group-item">
                                <a href="?category=<?= $category['id']; ?><?php if(isset($search) && $search) echo '&search=' . urlencode($search); ?>&page=1" 
                                   class="d-flex justify-content-between align-items-center text-decoration-none 
                                   <?php if (isset($categoryId) && $categoryId == $category['id']) echo 'fw-bold text-primary'; ?>">
                                    <span><?= htmlspecialchars($category['name']); ?></span>
                                    <span class="badge bg-outline-primary rounded-pill">12</span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Price Filter (placeholder) -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-tag me-2"></i>Khoảng giá</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="range" class="form-range" min="0" max="5000000" value="1000000" id="priceRange">
                            <div class="d-flex justify-content-between small text-muted mt-1">
                                <span>0đ</span>
                                <span>500đ</span>
                                <span>5.000.000đ</span>
                            </div>
                        </div>
                        <button class="btn btn-outline-success w-100">Lọc giá</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8">
            <!-- Filters Bar -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <form method="GET" class="d-flex">
                                <input type="hidden" name="category" value="<?= htmlspecialchars($categoryId ?? ''); ?>">
                                <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..." 
                                       value="<?= htmlspecialchars($search ?? ''); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select">
                                <option>Sắp xếp: Mới nhất</option>
                                <option>Giá: Thấp → Cao</option>
                                <option>Giá: Cao → Thấp</option>
                                <option>Tên: A → Z</option>
                            </select>
                        </div>
                        <div class="col-md-5 text-end">
                            <span class="text-muted">Hiển thị <strong><?= count($products); ?></strong> sản phẩm</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if (!empty($products)): ?>
            <div class="row g-4 mb-5">
                <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="product-card h-100">
                        <!-- Badges -->
                        <div class="product-badges">
                            <?php if (isset($product['quantity']) && $product['quantity'] == 0): ?>
                                <span class="badge badge-outofstock">Hết hàng</span>
                            <?php endif; ?>
                            <?php if (isset($product['featured']) && $product['featured']): ?>
                                <span class="badge badge-hot">Hot</span>
                            <?php endif; ?>
                            <?php if (isset($product['sale_price']) && $product['sale_price']): ?>
                                <span class="badge badge-sale">-<?= round((1 - $product['sale_price'] / $product['price']) * 100); ?>%</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Product Image -->
                        <div class="product-image">
                            <a href="/websitebatminton/products/<?= htmlspecialchars($product['slug']); ?>">
                                <img src="<?= $product['image'] ? '/websitebatminton/storage/uploads/' . $product['image'] : '/websitebatminton/assets/images/product.jpg'; ?>" 
                                     alt="<?= htmlspecialchars($product['name']); ?>" class="img-fluid">
                            </a>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="product-info p-3">
                            <h6 class="product-name mb-2">
                                <a href="/websitebatminton/products/<?= htmlspecialchars($product['slug']); ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($product['name']); ?>
                                </a>
                            </h6>
                            <div class="product-price mb-3">
                                <?php if (isset($product['sale_price']) && $product['sale_price']): ?>
                                    <span class="sale-price fw-bold text-danger fs-5"><?= number_format($product['sale_price'], 0, ',', '.'); ?>đ</span>
                                    <span class="original-price text-muted text-decoration-line-through ms-2"><?= number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                <?php else: ?>
                                    <span class="price fw-bold fs-5 text-primary"><?= number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-stock mb-3">
                                <?php if (isset($product['quantity'])): ?>
                                    <?php if ($product['quantity'] == 0): ?>
                                        <span class="badge bg-danger">Hết hàng</span>
                                    <?php elseif ($product['quantity'] < 10): ?>
                                        <span class="badge bg-warning text-dark">Còn <?= $product['quantity']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Còn hàng</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-primary w-100 btn-add-cart <?= (isset($product['quantity']) && $product['quantity'] == 0) ? 'disabled opacity-50' : ''; ?>">
                                <i class="bi bi-cart-plus me-1"></i>
                                <?= (isset($product['quantity']) && $product['quantity'] == 0) ? 'Hết hàng' : 'Thêm giỏ hàng'; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Product pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?<?= (isset($search) && $search) ? 'search=' . urlencode($search) . '&' : ''; ?><?= (isset($categoryId) && $categoryId) ? 'category=' . $categoryId . '&' : ''; ?>page=<?= ($currentPage - 1); ?>">Trước</a>
                    </li>
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : ''; ?>">
                        <a class="page-link" href="?<?= (isset($search) && $search) ? 'search=' . urlencode($search) . '&' : ''; ?><?= (isset($categoryId) && $categoryId) ? 'category=' . $categoryId . '&' : ''; ?>page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?<?= (isset($search) && $search) ? 'search=' . urlencode($search) . '&' : ''; ?><?= (isset($categoryId) && $categoryId) ? 'category=' . $categoryId . '&' : ''; ?>page=<?= ($currentPage + 1); ?>">Sau</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted mb-4"></i>
                <h3>Chưa có sản phẩm</h3>
                <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại.</p>
                <a href="/websitebatminton/products" class="btn btn-primary">Xem tất cả sản phẩm</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>

