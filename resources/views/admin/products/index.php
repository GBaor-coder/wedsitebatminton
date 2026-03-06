<!-- Products List View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Quản lý sản phẩm</h5>
            </div>
            <div class="col-md-6 text-end">
                <a href="/websitebatminton/admin/products/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm sản phẩm
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="/websitebatminton/admin/products" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." 
                           value="<?php echo $search ?? ''; ?>">
                    <select name="category" class="form-select" style="width: 200px;">
                        <option value="">Tất cả danh mục</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo ($selected_category ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo $cat['name']; ?>
                            </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Giá KM</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php if ($product['image']): ?>
                                <img src="/websitebatminton/storage/uploads/<?php echo $product['image']; ?>" 
                                     alt="<?php echo $product['name']; ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover;" 
                                     class="rounded">
                                <?php else: ?>
                                <img src="/websitebatminton/assets/images/no-image.jpg" 
                                     alt="No Image" 
                                     style="width: 50px; height: 50px; object-fit: cover;" 
                                     class="rounded">
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo $product['name']; ?></strong>
                                <?php if ($product['featured']): ?>
                                <span class="badge bg-warning text-dark ms-1">Nổi bật</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $product['category_name'] ?? 'Chưa phân loại'; ?></td>
                            <td><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</td>
                            <td>
                                <?php if ($product['sale_price']): ?>
                                <?php echo number_format($product['sale_price'], 0, ',', '.'); ?> đ
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($product['quantity'] == 0): ?>
                                <span class="badge bg-danger">Hết hàng</span>
                                <?php elseif ($product['quantity'] < 10): ?>
                                <span class="badge bg-warning text-dark"><?php echo $product['quantity']; ?></span>
                                <?php else: ?>
                                <?php echo $product['quantity']; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $product['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo $product['status'] == 'active' ? 'Hoạt động' : 'Ẩn'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/websitebatminton/admin/products/edit?id=<?php echo $product['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="/websitebatminton/admin/products/delete" 
                                      class="d-inline" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">Không tìm thấy sản phẩm nào</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <li class="page-item <?php echo $pagination['page'] == $i ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . $search : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

