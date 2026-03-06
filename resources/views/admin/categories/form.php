<!-- Category Form View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-tags me-2"></i>
            <?php echo $category ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới'; ?>
        </h5>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo $category ? '/websitebatminton/admin/categories/update' : '/websitebatminton/admin/categories/store'; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <?php if ($category): ?>
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            <?php endif; ?>
            
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo $category['name'] ?? ''; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo $category['description'] ?? ''; ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php echo ($category['status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>
                        Hoạt động
                    </option>
                    <option value="inactive" <?php echo ($category['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>
                        Ẩn
                    </option>
                </select>
            </div>
            
            <div class="text-end">
                <a href="/websitebatminton/admin/categories" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu
                </button>
            </div>
        </form>
    </div>
</div>

