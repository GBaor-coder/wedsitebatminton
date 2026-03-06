<!-- User Form View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-user me-2"></i>
            <?php echo $user ? 'Chỉnh sửa người dùng' : 'Thêm người dùng mới'; ?>
        </h5>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo $user ? '/websitebatminton/admin/users/update' : '/websitebatminton/admin/users/store'; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <?php if ($user): ?>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo $user['name'] ?? ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo $user['email'] ?? ''; ?>" required <?php echo $user ? 'readonly' : ''; ?>>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Mật khẩu <?php echo $user ? '' : '<span class="text-danger">*</span>'; ?>
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               <?php echo $user ? '' : 'required'; ?>>
                        <?php if ($user): ?>
                        <small class="text-muted">Để trống nếu không thay đổi mật khẩu</small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="<?php echo $user['phone'] ?? ''; ?>">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <textarea class="form-control" id="address" name="address" rows="2"><?php echo $user['address'] ?? ''; ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="role" class="form-label">Vai trò</label>
                        <select class="form-select" id="role" name="role">
                            <option value="user" <?php echo ($user['role'] ?? 'user') == 'user' ? 'selected' : ''; ?>>
                                User
                            </option>
                            <option value="admin" <?php echo ($user['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>
                                Admin
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?php echo ($user['status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>
                                Hoạt động
                            </option>
                            <option value="inactive" <?php echo ($user['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>
                                Khóa
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="/websitebatminton/admin/users" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu
                </button>
            </div>
        </form>
    </div>
</div>

