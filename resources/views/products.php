<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Products Grid -->
            <div class="products-grid col-12">
                <!-- Filters Bar -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2">
                                <select class="form-select" name="brand" id="brand-filter">
                                    <option value="">Thương hiệu</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['id']); ?>" <?= (isset($brand) && $brand == $category['id']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="price" id="price-filter">
                                    <option value="">Lọc Giá</option>
                                    <option value="0-500000" <?= (isset($priceRange) && $priceRange == '0-500000') ? 'selected' : ''; ?>>Dưới 500k</option>
                                    <option value="500000-1000000" <?= (isset($priceRange) && $priceRange == '500000-1000000') ? 'selected' : ''; ?>>500k - 1tr</option>
                                    <option value="1000000-2000000" <?= (isset($priceRange) && $priceRange == '1000000-2000000') ? 'selected' : ''; ?>>1tr - 2tr</option>
                                    <option value="2000000-5000000" <?= (isset($priceRange) && $priceRange == '2000000-5000000') ? 'selected' : ''; ?>>2tr - 5tr</option>
                                    <option value="5000000-" <?= (isset($priceRange) && $priceRange == '5000000-') ? 'selected' : ''; ?>>Trên 5tr</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="color" id="color-filter">
                                    <option value="">Màu sắc</option>
                                    <option value="trang" <?= (isset($color) && $color == 'trang') ? 'selected' : ''; ?>>Trắng</option>
                                    <option value="den" <?= (isset($color) && $color == 'den') ? 'selected' : ''; ?>>Đen</option>
                                    <option value="xanh" <?= (isset($color) && $color == 'xanh') ? 'selected' : ''; ?>>Xanh</option>
                                    <option value="do" <?= (isset($color) && $color == 'do') ? 'selected' : ''; ?>>Đỏ</option>
                                    <option value="vang" <?= (isset($color) && $color == 'vang') ? 'selected' : ''; ?>>Vàng</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="size" id="size-filter">
                                    <option value="">Size Giày</option>
                                    <option value="36" <?= (isset($size) && $size == '36') ? 'selected' : ''; ?>>36</option>
                                    <option value="37" <?= (isset($size) && $size == '37') ? 'selected' : ''; ?>>37</option>
                                    <option value="38" <?= (isset($size) && $size == '38') ? 'selected' : ''; ?>>38</option>
                                    <option value="39" <?= (isset($size) && $size == '39') ? 'selected' : ''; ?>>39</option>
                                    <option value="40" <?= (isset($size) && $size == '40') ? 'selected' : ''; ?>>40</option>
                                    <option value="41" <?= (isset($size) && $size == '41') ? 'selected' : ''; ?>>41</option>
                                    <option value="42" <?= (isset($size) && $size == '42') ? 'selected' : ''; ?>>42</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="sort" id="sort-filter">
                                    <option value="newest" <?= (isset($sort) && $sort == 'newest') ? 'selected' : ''; ?>>Sắp xếp: Mới nhất</option>
                                    <option value="price_asc" <?= (isset($sort) && $sort == 'price_asc') ? 'selected' : ''; ?>>Giá: Thấp → Cao</option>
                                    <option value="price_desc" <?= (isset($sort) && $sort == 'price_desc') ? 'selected' : ''; ?>>Giá: Cao → Thấp</option>
                                    <option value="name_asc" <?= (isset($sort) && $sort == 'name_asc') ? 'selected' : ''; ?>>Tên: A → Z</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary w-100" id="apply-filters">Áp dụng</button>
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
                                <?php if (isset($product['quantity']) && $product['quantity'] == 0): ?>
                                    <button class="btn btn-primary w-100 disabled opacity-50" disabled>
                                        <i class="bi bi-cart-plus me-1"></i>
                                        Hết hàng
                                    </button>
                                <?php else: ?>
                                    <a href="/websitebatminton/cart/add?product_id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-primary w-100 btn-add-cart">
                                        <i class="bi bi-cart-plus me-1"></i>
                                        Thêm giỏ hàng
                                    </a>
                                <?php endif; ?>
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
</div>

<script>
document.getElementById('apply-filters').addEventListener('click', function() {
    const params = new URLSearchParams(window.location.search);
    
    // Get filter values
    const brand = document.getElementById('brand-filter').value;
    const price = document.getElementById('price-filter').value;
    const color = document.getElementById('color-filter').value;
    const size = document.getElementById('size-filter').value;
    const sort = document.getElementById('sort-filter').value;
    
    // Update or remove params
    if (brand) params.set('brand', brand); else params.delete('brand');
    if (price) params.set('price', price); else params.delete('price');
    if (color) params.set('color', color); else params.delete('color');
    if (size) params.set('size', size); else params.delete('size');
    if (sort) params.set('sort', sort); else params.delete('sort');
    
    // Reset page to 1 when filters change
    params.set('page', '1');
    
    // Redirect with new params
    window.location.href = window.location.pathname + '?' + params.toString();
});
</script>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>

