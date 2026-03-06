<!-- Users List View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Quản lý người dùng</h5>
            </div>
            <div class="col-md-6 text-end">
                <a href="/websitebatminton/admin/users/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm người dùng
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Search -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="/websitebatminton/admin/users" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm người dùng..." 
                           value="<?php echo $search ?? ''; ?>">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><strong><?php echo $user['name']; ?></strong></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone'] ?? '-'; ?></td>
                            <td>
                                <?php if ($user['role'] == 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                <span class="badge bg-info">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $user['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo $user['status'] == 'active' ? 'Hoạt động' : 'Khóa'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <a href="/websitebatminton/admin/users/edit?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['id'] != $admin_user['id']): ?>
                                <form method="POST" action="/websitebatminton/admin/users/delete" 
                                      class="d-inline" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">Không tìm thấy người dùng nào</p>
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

