<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<div class="lookup-page py-5" style="background: linear-gradient(180deg, #eef4ff 0%, #f8fbff 100%); min-height: calc(100vh - 140px);">
    <div class="container">
        <!-- Lookup Form Card -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 col-lg-5">
                <div class="lookup-card card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="lookup-icon mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: linear-gradient(135deg, #1C42F3, #4a68f6);">
                                <i class="bi bi-box-seam text-white fs-3"></i>
                            </div>
                            <h3 class="fw-bold mb-1">Tra cứu đơn hàng</h3>
                            <p class="text-muted small mb-0">Nhập mã đơn hàng hoặc số điện thoại để kiểm tra</p>
                        </div>
                        
                        <form action="/websitebatminton/tra-cuu-don-hang" method="GET">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Mã đơn hàng</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-upc-scan text-primary"></i>
                                    </span>
                                    <input type="text" name="order_number" class="form-control border-start-0 ps-0" 
                                           placeholder="VD: ORD-20260101-ABC123" 
                                           value="<?= htmlspecialchars($orderNumber ?? '') ?>"
                                           style="border-radius: 0 10px 10px 0;">
                                </div>
                            </div>
                            
                            <div class="text-center mb-3">
                                <span class="text-muted small fw-medium">— HOẶC —</span>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-telephone text-primary"></i>
                                    </span>
                                    <input type="text" name="phone" class="form-control border-start-0 ps-0" 
                                           placeholder="VD: 0342826430" 
                                           value="<?= htmlspecialchars($phone ?? '') ?>"
                                           style="border-radius: 0 10px 10px 0;">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="border-radius: 12px; background: linear-gradient(135deg, #1C42F3, #4a68f6); border: none;">
                                <i class="bi bi-search me-2"></i>Tra cứu ngay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <?php if (isset($order)): ?>
            <?php if ($order): ?>
                <!-- Order Found -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="result-card card border-0 shadow-sm rounded-4 overflow-hidden">
                            <!-- Result Header -->
                            <div class="result-header p-4 text-white" style="background: linear-gradient(135deg, #1C42F3, #4a68f6);">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            <i class="bi bi-check-circle-fill me-2"></i>Đơn hàng đã tìm thấy
                                        </h5>
                                        <p class="mb-0 opacity-75 small">Mã đơn: <strong><?= htmlspecialchars($order['order_number']) ?></strong></p>
                                    </div>
                                    <?php
                                    $statusClass = 'bg-white text-dark';
                                    $statusText = $order['status'];
                                    switch($order['status']) {
                                        case 'pending':
                                            $statusClass = 'bg-warning text-dark';
                                            $statusText = 'Chờ xử lý';
                                            break;
                                        case 'processing':
                                            $statusClass = 'bg-info text-white';
                                            $statusText = 'Đang xử lý';
                                            break;
                                        case 'shipped':
                                            $statusClass = 'bg-primary text-white';
                                            $statusText = 'Đã gửi hàng';
                                            break;
                                        case 'completed':
                                            $statusClass = 'bg-success text-white';
                                            $statusText = 'Hoàn thành';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'bg-danger text-white';
                                            $statusText = 'Đã hủy';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?> px-3 py-2 fs-6 rounded-pill"><?= $statusText ?></span>
                                </div>
                            </div>
                            
                            <!-- Result Body -->
                            <div class="card-body p-4">
                                <!-- Customer Info -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="info-item p-3 rounded-3" style="background: #f8f9ff;">
                                            <small class="text-muted d-block mb-1">Khách hàng</small>
                                            <strong class="text-dark"><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item p-3 rounded-3" style="background: #f8f9ff;">
                                            <small class="text-muted d-block mb-1">Số điện thoại</small>
                                            <strong class="text-dark"><?= htmlspecialchars($order['customer_phone'] ?? 'N/A') ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item p-3 rounded-3" style="background: #f8f9ff;">
                                            <small class="text-muted d-block mb-1">Ngày đặt hàng</small>
                                            <strong class="text-dark"><?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : 'N/A' ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item p-3 rounded-3" style="background: #f8f9ff;">
                                            <small class="text-muted d-block mb-1">Phương thức thanh toán</small>
                                            <strong class="text-dark">
                                                <?php 
                                                $paymentMethods = [
                                                    'cod' => 'Thanh toán khi nhận hàng (COD)',
                                                    'bank' => 'Chuyển khoản ngân hàng',
                                                    'momo' => 'Ví MoMo',
                                                    'vnpay' => 'VNPay'
                                                ];
                                                echo htmlspecialchars($paymentMethods[$order['payment_method'] ?? ''] ?? ($order['payment_method'] ?? 'N/A'));
                                                ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Shipping Address -->
                                <div class="mb-4 p-3 rounded-3 border" style="border-color: #e0e7ff !important;">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-geo-alt me-1 text-primary"></i>Địa chỉ giao hàng
                                    </small>
                                    <strong class="text-dark">
                                        <?= htmlspecialchars($order['shipping_address'] ?? $order['customer_address'] ?? 'N/A') ?>, 
                                        <?= htmlspecialchars($order['shipping_city'] ?? '') ?>
                                    </strong>
                                </div>
                                
                                <!-- Order Items -->
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-cart-check me-2 text-primary"></i>Sản phẩm đã đặt
                                </h6>
                                
                                <?php if (!empty($items)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-borderless align-middle">
                                            <thead class="table-light rounded-3">
                                                <tr>
                                                    <th class="ps-3">Sản phẩm</th>
                                                    <th class="text-center">Số lượng</th>
                                                    <th class="text-end pe-3">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($items as $item): ?>
                                                <tr>
                                                    <td class="ps-3">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <?php if (!empty($item['product_image'])): ?>
                                                                <img src="/websitebatminton/storage/uploads/<?= htmlspecialchars($item['product_image']) ?>" 
                                                                     alt="" class="rounded-2" style="width: 48px; height: 48px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="rounded-2 bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                                    <i class="bi bi-image text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                            <span class="fw-medium"><?= htmlspecialchars($item['product_name'] ?? $item['product_name']) ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">x<?= $item['quantity'] ?></td>
                                                    <td class="text-end pe-3 fw-bold text-primary"><?= number_format($item['subtotal'] ?? ($item['product_price'] * $item['quantity']), 0, ',', '.') ?>₫</td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">Không có thông tin sản phẩm.</p>
                                <?php endif; ?>
                                
                                <!-- Order Summary -->
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Tạm tính</span>
                                        <span><?= number_format($order['subtotal'] ?? 0, 0, ',', '.') ?>₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Phí vận chuyển</span>
                                        <span><?= number_format($order['shipping_fee'] ?? 0, 0, ',', '.') ?>₫</span>
                                    </div>
                                    <?php if (($order['discount_amount'] ?? 0) > 0): ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Giảm giá</span>
                                        <span class="text-danger">-<?= number_format($order['discount_amount'], 0, ',', '.') ?>₫</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between pt-2 border-top">
                                        <span class="fw-bold fs-5">Tổng cộng</span>
                                        <span class="fw-bold fs-5 text-primary"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>₫</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Order Not Found -->
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="not-found-card card border-0 shadow-sm rounded-4 text-center p-5">
                            <div class="not-found-icon mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: #fff5f5;">
                                <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Không tìm thấy đơn hàng</h4>
                            <p class="text-muted mb-4">Vui lòng kiểm tra lại mã đơn hàng hoặc số điện thoại và thử lại.</p>
                            <a href="/websitebatminton/products" class="btn btn-primary fw-bold px-4" style="border-radius: 12px; background: linear-gradient(135deg, #1C42F3, #4a68f6); border: none;">
                                <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.lookup-page { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
.lookup-card { border-radius: 24px !important; box-shadow: 0 25px 50px rgba(43, 60, 255, 0.08) !important; }
.lookup-icon { box-shadow: 0 8px 20px rgba(28, 66, 243, 0.3); }
.result-card { border-radius: 24px !important; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08) !important; }
.result-header { border-radius: 24px 24px 0 0; }
.info-item { transition: transform 0.2s; }
.info-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(28, 66, 243, 0.08); }
.not-found-card { border-radius: 24px !important; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06) !important; }
.not-found-icon { box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15); }
.table thead th { font-size: 0.85rem; letter-spacing: 0.5px; }
.table tbody td { padding-top: 12px; padding-bottom: 12px; }
@media (max-width: 768px) {
    .lookup-card .card-body { padding: 1.5rem !important; }
    .result-header h5 { font-size: 1rem; }
}
</style>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>

