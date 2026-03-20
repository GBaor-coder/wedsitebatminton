


<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?= $post ? 'Chỉnh sửa bài viết' : 'Thêm bài viết mới'; ?></h1>
        <a href="/websitebatminton/admin/posts" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="<?= $post ? '/websitebatminton/admin/posts/update' : '/websitebatminton/admin/posts/store'; ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                <?php if ($post): ?>
                    <input type="hidden" name="id" value="<?= $post['id']; ?>">
                <?php endif; ?>
                
                <div class="row g-4">
                    <!-- Title -->
                    <div class="col-md-8">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title'] ?? ''); ?>" required>
                            <label for="title">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <div class="invalid-feedback">Tiêu đề không được để trống</div>
                        </div>
                        
                        <!-- Category -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="category" name="category">
                                <option value="news" <?= ( ($post['category'] ?? '') == 'news' ) ? 'selected' : ''; ?>>Tin tức</option>
                                <option value="guide" <?= ( ($post['category'] ?? '') == 'guide' ) ? 'selected' : ''; ?>>Hướng dẫn</option>
                                <option value="review" <?= ( ($post['category'] ?? '') == 'review' ) ? 'selected' : ''; ?>>Đánh giá</option>
                            </select>
                            <label for="category">Danh mục</label>
                        </div>
                    </div>
                    
                    <!-- Image -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Hình ảnh</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <?php if ($post && $post['image']): ?>
                                <div class="mt-2">
                                    <img src="/websitebatminton/storage/uploads/<?= htmlspecialchars($post['image']); ?>" class="rounded shadow-sm" style="max-width: 200px; max-height: 150px;">
                                    <small class="text-muted d-block">Hình hiện tại</small>
                                </div>
                            <?php endif; ?>
                            <div class="form-text">JPG, PNG (max 2MB)</div>
                        </div>
                        
                        <!-- Featured & Status -->
                        <div class="row g-3 mt-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured" <?= (isset($post['featured']) && $post['featured']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-bold" for="featured">
                                        Nổi bật
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <select class="form-select form-select-sm" name="status">
                                    <option value="active" <?= ($post['status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                                    <option value="inactive" <?= ($post['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Ẩn</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Excerpt -->
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" id="excerpt" name="excerpt" style="height: 100px" placeholder="Tóm tắt ngắn"><?= htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                            <label for="excerpt">Tóm tắt (excerpt)</label>
                            <div class="form-text">Hiển thị trước content, tối đa 160 ký tự</div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="col-12">
                        <label for="content" class="form-label fw-bold mb-2">Nội dung bài viết <span class="text-danger">*</span></label>
                        <textarea id="content" name="content"><?= htmlspecialchars($post['content'] ?? ''); ?></textarea>
                        <div class="form-text mt-2">Nhập nội dung HTML trực tiếp</div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= $post ? 'Cập nhật' : 'Đăng bài'; ?>
                    </button>
                    <a href="/websitebatminton/admin/posts" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>


