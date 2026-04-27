<?php include isset($layout) ? $layout : ROOT_PATH . '/resources/views/layouts/header.php'; ?>
<?php $session = new \Session(); ?>

<div class="container py-5">
    <div class="row">
        <!-- Order Status Section -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="mb-2">ĐƠN HÀNG <?php echo htmlspecialchars($order['order_number']); ?></h2>
                            <p class="text-muted mb-0">Ngày đặt hàng: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <?php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $statusLabels = [
                                    'pending' => 'Chờ xử lý',
                                    'processing' => 'Đang xử lý',
                                    'shipped' => 'Đã gửi',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy'
                                ];
                                $status = $order['status'] ?? 'pending';
                                $badgeColor = $statusColors[$status] ?? 'secondary';
                                $statusLabel = $statusLabels[$status] ?? $status;
                            ?>
                            <span class="badge bg-<?php echo $badgeColor; ?> p-2" style="font-size: 14px;">
                                <?php echo $statusLabel; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">CHI TIẾT SẢN PHẨM</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Số lượng</th>
                                    <th class="text-end">Giá</th>
                                    <th class="text-end">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($item['product_image'])): ?>
                                                        <img src="/websitebatminton/storage/uploads/<?php echo htmlspecialchars($item['product_image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($item['product_name'] ?? 'Sản phẩm'); ?>" 
                                                             style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                                    <?php endif; ?>
                                                    <span><?php echo htmlspecialchars($item['product_name'] ?? 'Sản phẩm'); ?></span>
                                                </div>
                                            </td>
                                            <td class="text-end"><?php echo $item['quantity']; ?></td>
                                            <td class="text-end"><?php echo number_format($item['price'] ?? $item['product_price'] ?? 0, 0, ',', '.'); ?>đ</td>
                                            <td class="text-end"><strong><?php echo number_format($item['subtotal'] ?? $item['total'] ?? 0, 0, ',', '.'); ?>đ</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Không có sản phẩm</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">THÔNG TIN GIAO HÀNG</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Địa chỉ giao hàng</h6>
                            <p class="mb-1"><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_city']); ?>, <?php echo htmlspecialchars($order['shipping_country']); ?></p>
                            <p class="text-muted">
                                <i class="fas fa-phone"></i> <?php echo htmlspecialchars($order['customer_phone']); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Phương thức giao hàng</h6>
                            <p class="mb-1">
                                <?php
                                    $shippingMethods = [
                                        'standard' => 'Giao hàng tiêu chuẩn (3-5 ngày)',
                                        'express' => 'Giao hàng nhanh (1-2 ngày)',
                                    ];
                                    $shippingMethod = $order['shipping_method'] ?? 'standard';
                                    echo $shippingMethods[$shippingMethod] ?? $shippingMethod;
                                ?>
                            </p>
                            <p class="text-muted mb-0">Phí vận chuyển: <strong><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?>đ</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">THÔNG TIN THANH TOÁN</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">
                        <strong>Phương thức:</strong>
                        <?php
                            $paymentMethods = [
                                'cod' => 'Thanh toán khi nhận hàng (COD)',
                                'bank' => 'Chuyển khoản ngân hàng',
                                'card' => 'Thẻ tín dụng/Ghi nợ'
                            ];
                            $paymentMethod = $order['payment_method'] ?? 'cod';
                            echo $paymentMethods[$paymentMethod] ?? $paymentMethod;
                        ?>
                    </p>
                    <p class="text-muted mb-0">
                        <small>Email: <?php echo htmlspecialchars($order['customer_email']); ?></small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">TỔNG HỢP ĐƠN HÀNG</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tiền hàng:</span>
                            <strong><?php echo number_format($order['subtotal'], 0, ',', '.'); ?>đ</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phí vận chuyển:</span>
                            <strong><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?>đ</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-4" style="font-size: 18px;">
                        <strong>Tổng cộng:</strong>
                        <strong style="color: var(--main-color);">
                            <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ
                        </strong>
                    </div>

                    <!-- Status Timeline -->
                    <div class="mb-4">
                        <h6 class="mb-3">TRẠNG THÁI ĐƠN HÀNG</h6>
                        <div class="timeline">
                            <?php
                                $timeline = [
                                    ['status' => 'pending', 'label' => 'Chờ xử lý', 'icon' => 'fa-hourglass-start'],
                                    ['status' => 'processing', 'label' => 'Đang xử lý', 'icon' => 'fa-cogs'],
                                    ['status' => 'shipped', 'label' => 'Đã gửi', 'icon' => 'fa-truck'],
                                    ['status' => 'completed', 'label' => 'Hoàn thành', 'icon' => 'fa-check-circle']
                                ];
                                $statusIndex = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'completed' => 3, 'cancelled' => -1];
                                $currentIndex = $statusIndex[$order['status']] ?? 0;
                            ?>
                            <?php foreach ($timeline as $key => $step): ?>
                                <div class="timeline-item mb-3" style="padding-left: 30px; position: relative;">
                                    <div style="position: absolute; left: 0; top: 0; width: 24px; height: 24px; border-radius: 50%; background: <?php echo ($currentIndex >= $key) ? 'var(--main-color)' : '#e0e0e0'; ?>; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
                                        <i class="fas <?php echo $step['icon']; ?>" style="font-size: 10px;"></i>
                                    </div>
                                    <p class="mb-0" style="font-size: 14px; color: <?php echo ($currentIndex >= $key) ? '#000' : '#999'; ?>">
                                        <?php echo $step['label']; ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <a href="/websitebatminton/my-orders" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'completed'): ?>
                        <button type="button" class="btn btn-outline-danger w-100" id="cancelOrderBtn">
                            <i class="fas fa-times"></i> Hủy đơn hàng
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('cancelOrderBtn')?.addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            alert('Chức năng hủy đơn hàng sẽ được triển khai sớm');
        }
    });
</script>

<?php include isset($layout) ? '' : ROOT_PATH . '/resources/views/layouts/footer.php'; ?>
