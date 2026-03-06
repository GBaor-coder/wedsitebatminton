<!-- Orders List View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng</h5>
    </div>
    
    <div class="card-body">
        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="/websitebatminton/admin/orders" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm đơn hàng..." 
                           value="<?php echo $search ?? ''; ?>">
                    <select name="status" class="form-select" style="width: 180px;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" <?php echo ($selected_status ?? '') == 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                        <option value="processing" <?php echo ($selected_status ?? '') == 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                        <option value="shipped" <?php echo ($selected_status ?? '') == 'shipped' ? 'selected' : ''; ?>>Đang giao</option>
                        <option value="completed" <?php echo ($selected_status ?? '') == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                        <option value="cancelled" <?php echo ($selected_status ?? '') == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Orders Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong><?php echo $order['order_number']; ?></strong></td>
                            <td>
                                <?php echo $order['user_name']; ?><br>
                                <small class="text-muted"><?php echo $order['user_email'] ?? ''; ?></small>
                            </td>
                            <td><strong><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</strong></td>
                            <td><?php echo $order['payment_method'] ?? 'COD'; ?></td>
                            <td>
                                <?php 
                                $statusClass = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $status = $order['status'];
                                ?>
                                <span class="badge bg-<?php echo $statusClass[$status] ?? 'secondary'; ?>">
                                    <?php 
                                    $statusText = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đang giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    echo $statusText[$status] ?? $status;
                                    ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="/websitebatminton/admin/orders/view?id=<?php echo $order['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($order['status'] != 'completed' && $order['status'] != 'cancelled'): ?>
                                <div class="dropdown d-inline">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if ($order['status'] == 'pending'): ?>
                                        <li>
                                            <button class="dropdown-item" onclick="updateStatus(<?php echo $order['id']; ?>, 'processing')">
                                                Xác nhận đơn hàng
                                            </button>
                                        </li>
                                        <?php endif; ?>
                                        <?php if ($order['status'] == 'processing'): ?>
                                        <li>
                                            <button class="dropdown-item" onclick="updateStatus(<?php echo $order['id']; ?>, 'shipped')">
                                                Giao hàng
                                            </button>
                                        </li>
                                        <?php endif; ?>
                                        <?php if ($order['status'] == 'shipped'): ?>
                                        <li>
                                            <button class="dropdown-item" onclick="updateStatus(<?php echo $order['id']; ?>, 'completed')">
                                                Hoàn thành
                                            </button>
                                        </li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item text-danger" onclick="updateStatus(<?php echo $order['id']; ?>, 'cancelled')">
                                                Hủy đơn
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">Không tìm thấy đơn hàng nào</p>
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
                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . $search : ''; ?><?php echo $selected_status ? '&status=' . $selected_status : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<script>
function updateStatus(orderId, status) {
    if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
        $.ajax({
            url: '/websitebatminton/admin/orders/status',
            type: 'POST',
            data: {
                id: orderId,
                status: status,
                csrf_token: '<?php echo $csrf_token; ?>'
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        });
    }
}
</script>

