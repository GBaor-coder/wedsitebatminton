<!-- Dashboard Content -->
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3">
        <div class="card stat-card blue mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Tổng sản phẩm</h6>
                    <h2 class="mb-0"><?php echo $product_stats['total'] ?? 0; ?></h2>
                </div>
                <div>
                    <i class="fas fa-box fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="mt-2">
                <small>Hoạt động: <?php echo $product_stats['active'] ?? 0; ?></small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card green mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Tổng đơn hàng</h6>
                    <h2 class="mb-0"><?php echo $order_stats['total'] ?? 0; ?></h2>
                </div>
                <div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="mt-2">
                <small>Hôm nay: <?php echo $order_stats['today'] ?? 0; ?></small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card orange mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Đơn hàng chờ</h6>
                    <h2 class="mb-0"><?php echo $order_stats['pending'] ?? 0; ?></h2>
                </div>
                <div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="mt-2">
                <small>Đang xử lý: <?php echo $order_stats['processing'] ?? 0; ?></small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card red mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Doanh thu</h6>
                    <h2 class="mb-0"><?php echo number_format($order_stats['revenue'] ?? 0, 0, ',', '.'); ?> đ</h2>
                </div>
                <div>
                    <i class="fas fa-money-bill fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="mt-2">
                <small>Hoàn thành: <?php echo $order_stats['completed'] ?? 0; ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables -->
<div class="row mt-4">
    <!-- Recent Orders -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Đơn hàng gần đây</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_orders)): ?>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_number']; ?></td>
                                    <td><?php echo $order['user_name']; ?></td>
                                    <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</td>
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
                                    <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <a href="/websitebatminton/admin/orders/view?id=<?php echo $order['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">Không có đơn hàng nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <a href="/websitebatminton/admin/orders" class="btn btn-outline-primary">
                        Xem tất cả đơn hàng <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Products -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Sản phẩm sắp hết hàng</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($low_stock_products)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($low_stock_products as $product): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="mb-1"><?php echo $product['name']; ?></h6>
                                <small class="text-muted"><?php echo $product['category_name'] ?? ''; ?></small>
                            </div>
                            <span class="badge bg-danger"><?php echo $product['quantity']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-4">Không có sản phẩm sắp hết hàng</p>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="/websitebatminton/admin/products" class="btn btn-outline-primary">
                        Xem tất cả sản phẩm <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="/websitebatminton/admin/products/create" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <br>Thêm sản phẩm
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/websitebatminton/admin/categories/create" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <br>Thêm danh mục
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/websitebatminton/admin/orders" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <br>Quản lý đơn hàng
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/websitebatminton/admin/users/create" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <br>Thêm người dùng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

