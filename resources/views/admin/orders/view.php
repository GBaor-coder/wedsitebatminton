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
                                <td><?php echo $order['shipping_name'] ?? $order['customer_name']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Điện thoại:</strong></td>
                                <td><?php echo $order['shipping_phone'] ?? $order['customer_phone']; ?></td>
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
                                    <td><?php echo number_format($item['price'] ?? $item['product_price'] ?? 0, 0, ',', '.'); ?> đ</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><strong><?php echo number_format($item['total'] ?? $item['subtotal'] ?? 0, 0, ',', '.'); ?> đ</strong></td>
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
                                <td colspan="3" class="text-end"><strong>Tạm tính:</strong></td>
                                <td><strong><?php echo number_format($order['subtotal'] ?? 0, 0, ',', '.'); ?> đ</strong></td>
                            </tr>
                            <?php if (!empty($order['shipping_fee']) && $order['shipping_fee'] > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                <td><strong><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?> đ</strong></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Giảm giá:</strong></td>
                                <td><strong class="text-success">-<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?> đ</strong></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td><strong class="text-danger h5"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Toast Notification -->
        <div id="statusToast" class="toast align-items-center text-white border-0 mt-3" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;">
            <div class="d-flex">
                <div class="toast-body" id="statusToastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Update Order Status -->
            <?php if ($order['status'] != 'completed' && $order['status'] != 'cancelled'): ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Cập nhật trạng thái đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/websitebatminton/admin/orders/status" id="statusForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                            <div class="row">
                                <div class="col-md-8">
                                    <select name="status" class="form-select" id="statusSelect">
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
                                    <button type="submit" class="btn btn-primary w-100" id="statusSubmitBtn">
                                        <i class="fas fa-save"></i> Cập nhật
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Update Payment Status -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Cập nhật trạng thái thanh toán</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/websitebatminton/admin/orders/payment-status" id="paymentStatusForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                            <div class="row">
                                <div class="col-md-8">
                                    <select name="payment_status" class="form-select" id="paymentStatusSelect">
                                        <option value="pending" <?php echo ($order['payment_status'] ?? 'pending') == 'pending' ? 'selected' : ''; ?>>Chưa thanh toán</option>
                                        <option value="paid" <?php echo ($order['payment_status'] ?? '') == 'paid' ? 'selected' : ''; ?>>Đã thanh toán</option>
                                        <option value="failed" <?php echo ($order['payment_status'] ?? '') == 'failed' ? 'selected' : ''; ?>>Thanh toán thất bại</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success w-100" id="paymentStatusSubmitBtn">
                                        <i class="fas fa-credit-card"></i> Cập nhật
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Order Status Form
        var statusForm = document.getElementById('statusForm');
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmit(this, 'statusSubmitBtn', 'statusSelect', 'Cập nhật trạng thái đơn hàng thành công');
            });
        }

        // Payment Status Form
        document.getElementById('paymentStatusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmit(this, 'paymentStatusSubmitBtn', 'paymentStatusSelect', 'Cập nhật trạng thái thanh toán thành công');
        });

        function handleFormSubmit(form, btnId, selectId, successMessage) {
            var btn = document.getElementById(btnId);
            var originalText = btn.innerHTML;
            var select = document.getElementById(selectId);

            if (select && !select.value) {
                showStatusToast('Vui lòng chọn trạng thái', 'bg-warning');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';

            var formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = originalText;

                if (data.success) {
                    showStatusToast(data.message || successMessage, 'bg-success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showStatusToast(data.message || 'Cập nhật thất bại', 'bg-danger');
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = originalText;
                showStatusToast('Có lỗi xảy ra. Vui lòng thử lại.', 'bg-danger');
            });
        }

        function showStatusToast(message, bgClass) {
            var toast = document.getElementById('statusToast');
            var body = document.getElementById('statusToastBody');
            body.textContent = message;
            toast.className = 'toast align-items-center text-white border-0 mt-3 ' + bgClass;
            toast.style.display = 'flex';
            setTimeout(function() {
                toast.style.display = 'none';
            }, 4000);
        }
        </script>
    </div>
</div>

