<!-- Categories List View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Quản lý danh mục</h5>
            </div>
            <div class="col-md-6 text-end">
                <a href="/websitebatminton/admin/categories/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm danh mục
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Search -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="/websitebatminton/admin/categories" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..." 
                           value="<?php echo $search ?? ''; ?>">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Categories Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Số sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><strong><?php echo $category['name']; ?></strong></td>
                            <td><code><?php echo $category['slug']; ?></code></td>
                            <td>
                                <span class="badge bg-info"><?php echo $category['product_count'] ?? 0; ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $category['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo $category['status'] == 'active' ? 'Hoạt động' : 'Ẩn'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/websitebatminton/admin/categories/edit?id=<?php echo $category['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="/websitebatminton/admin/categories/delete" 
                                      class="d-inline" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">Không tìm thấy danh mục nào</p>
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

