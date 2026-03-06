<!-- Order Detail View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Chi tiết đơn hàng #<?php echo $order['order_number']; ?>
                </h5>
            </div>
            <div class="col-md-6 text-end">
                <a href="/websitebatminton/admin/orders" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <!-- Order Info -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Thông tin đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Mã đơn hàng:</strong></td>
                                <td><?php echo $order['order_number']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ngày đặt:</strong></td>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($order['created_at'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    <?php 
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusText = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đang giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass[$order['status']] ?? 'secondary'; ?>">
                                        <?php echo $statusText[$order['status']] ?? $order['status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Phương thức thanh toán:</strong></td>
                                <td><?php echo $order['payment_method'] ?? 'COD'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái thanh toán:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo $order['payment_status'] == 'paid' ? 'success' : 'warning'; ?>">
                                        <?php echo $order['payment_status'] == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Thông tin khách hàng</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Tên khách hàng:</strong></td>
                                <td><?php echo $order['shipping_name'] ?? $order['user_name']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Điện thoại:</strong></td>
                                <td><?php echo $order['shipping_phone'] ?? ''; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ:</strong></td>
                                <td><?php echo $order['shipping_address'] ?? ''; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ghi chú:</strong></td>
                                <td><?php echo $order['notes'] ?? 'Không có'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Sản phẩm đặt mua</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['product_image']): ?>
                                            <img src="/websitebatminton/storage/uploads/<?php echo $item['product_image']; ?>" 
                                                 alt="<?php echo $item['product_name']; ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                 class="me-2 rounded">
                                            <?php endif; ?>
                                            <span><?php echo $item['product_name']; ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><strong><?php echo number_format($item['total'], 0, ',', '.'); ?> đ</strong></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">Không có sản phẩm</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td><strong class="text-danger h5"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Update Status -->
        <?php if ($order['status'] != 'completed' && $order['status'] != 'cancelled'): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Cập nhật trạng thái</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/websitebatminton/admin/orders/status" id="statusForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                    <div class="row">
                        <div class="col-md-8">
                            <select name="status" class="form-select">
                                <option value="">Chọn trạng thái</option>
                                <?php if ($order['status'] == 'pending'): ?>
                                <option value="processing">Xác nhận đơn hàng</option>
                                <?php endif; ?>
                                <?php if ($order['status'] == 'processing'): ?>
                                <option value="shipped">Giao hàng</option>
                                <?php endif; ?>
                                <?php if ($order['status'] == 'shipped'): ?>
                                <option value="completed">Hoàn thành</option>
                                <?php endif; ?>
                                <option value="cancelled">Hủy đơn hàng</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

