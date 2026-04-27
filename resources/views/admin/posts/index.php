<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý bài viết</h1>
        <a href="/websitebatminton/admin/posts/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Thêm mới
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tiêu đề..." value="<?= htmlspecialchars($search ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="inactive" <?= (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>Ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-search me-1"></i>Tìm
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="/websitebatminton/admin/posts" class="btn btn-secondary w-100">Xóa filter</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($posts)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-text display-1 text-muted mb-3"></i>
                    <h4>Chưa có bài viết</h4>
                    <p class="text-muted">Tạo bài viết đầu tiên để bắt đầu</p>
                    <a href="/websitebatminton/admin/posts/create" class="btn btn-primary">Tạo bài viết</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Tiêu đề</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                <th>Hình ảnh</th>
                                <th>Ngày tạo</th>
                                <th style="width: 120px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $index => $post): ?>
                            <tr>
                                <td><?= (($page - 1) * $perPage) + $index + 1; ?></td>
                                <td>
                                    <div>
                                        <?php if ($post['featured']): ?>
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                        <?php endif; ?>
                                        <strong><?= htmlspecialchars(substr($post['title'], 0, 60)); ?>...</strong>
                                    </div>
                                    <small class="text-muted"><?= htmlspecialchars(substr($post['excerpt'], 0, 80)); ?>...</small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= htmlspecialchars($post['category'] ?? 'News'); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $post['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                        <?= $post['status'] == 'active' ? 'Hoạt động' : 'Ẩn'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($post['image']): ?>
                                        <img src="/websitebatminton/storage/uploads/<?= htmlspecialchars($post['image']); ?>" alt="" class="rounded" style="width: 50px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($post['created_at'])); ?></small></td>
                                <td>
                                    <a href="/websitebatminton/admin/posts/edit?id=<?= $post['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="/websitebatminton/admin/posts/delete" 
                                          class="d-inline" onsubmit="return confirm('Xóa bài viết này?');">
                                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                                        <input type="hidden" name="id" value="<?= $post['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <nav class="d-flex justify-content-center mt-4">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?= $pagination['page'] == $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?= $i; ?><?= !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <?= $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


