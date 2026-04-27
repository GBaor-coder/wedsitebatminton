<?php include isset($layout) ? $layout : ROOT_PATH . '/resources/views/layouts/header.php'; ?>
<?php $session = new \Session(); ?>

<div class="container py-5">
    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-8 mb-4">
            <!-- Billing Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">THÔNG TIN THANH TOÁN</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" 
                                   value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="address" 
                                   value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" 
                                   placeholder="Nhập địa chỉ giao hàng" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Thành phố <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="city" 
                                       value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Quốc gia <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="country" 
                                       value="<?php echo htmlspecialchars($user['country'] ?? 'Vietnam'); ?>" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Shipping Method -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">PHƯƠNG THỨC GIAO HÀNG</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping" id="standard" value="standard" checked>
                        <label class="form-check-label" for="standard">
                            <strong>Giao hàng tiêu chuẩn</strong> - 15.000đ (3-5 ngày làm việc)
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping" id="express" value="express">
                        <label class="form-check-label" for="express">
                            <strong>Giao hàng nhanh</strong> - 30.000đ (1-2 ngày làm việc)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">PHƯƠNG THỨC THANH TOÁN</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment" id="cod" value="cod" checked>
                        <label class="form-check-label" for="cod">
                            <strong>Thanh toán khi nhận hàng (COD)</strong>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment" id="bank" value="bank">
                        <label class="form-check-label" for="bank">
                            <strong>Chuyển khoản ngân hàng</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment" id="card" value="card">
                        <label class="form-check-label" for="card">
                            <strong>Thẻ tín dụng/Ghi nợ</strong>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">ĐƠN HÀNG CỦA BẠN</h5>
                </div>
                <div class="card-body">
                    <!-- Order Items -->
                    <div class="mb-3">
                        <?php if (!empty($cartItems)): ?>
                            <?php $subtotal = 0; ?>
                            <?php foreach ($cartItems as $item): ?>
                                <?php 
                                    $price = $item['sale_price'] ?? $item['price'];
                                    $itemTotal = $price * $item['quantity'];
                                    $subtotal += $itemTotal;
                                ?>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div>
                                        <small class="text-muted"><?php echo htmlspecialchars($item['name']); ?></small><br>
                                        <small class="text-muted">x<?php echo $item['quantity']; ?></small>
                                    </div>
                                    <small><strong><?php echo number_format($itemTotal, 0, ',', '.'); ?>đ</strong></small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Pricing Summary -->
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tiền hàng:</span>
                            <strong><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <strong id="shippingFee">15.000đ</strong>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="d-flex justify-content-between mb-4" style="font-size: 18px;">
                        <strong>Tổng cộng:</strong>
                        <strong id="totalPrice" style="color: var(--main-color);">
                            <?php echo number_format($subtotal + 15000, 0, ',', '.'); ?>đ
                        </strong>
                    </div>

                    <!-- Promo Code -->
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-sm" 
                               placeholder="Mã giảm giá" id="promoCode">
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-3">
                         Áp dụng mã giảm giá
                    </button>

                    <!-- Checkout Button -->
                    <button type="submit" form="checkoutForm" class="btn btn-primary w-100" 
                            id="checkoutBtn">
                        HOÀN TẤT THANH TOÁN
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const fees = {
                'standard': 15000,
                'express': 30000,
                'overnight': 50000
            };
            const subtotal = <?php echo $subtotal; ?>;
            const shippingFee = fees[this.value];
            
            document.getElementById('shippingFee').textContent = 
                shippingFee.toLocaleString('vi-VN') + 'đ';
            
            document.getElementById('totalPrice').textContent = 
                (subtotal + shippingFee).toLocaleString('vi-VN') + 'đ';
        });
    });

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            address: formData.get('address'),
            city: formData.get('city'),
            country: formData.get('country'),
            shipping: document.querySelector('input[name="shipping"]:checked').value,
            payment: document.querySelector('input[name="payment"]:checked').value
        };

        // Validate form
        if (!data.name || !data.email || !data.phone || 
            !data.address || !data.city || !data.country) {
            alert('Vui lòng điền đầy đủ thông tin');
            return;
        }

        // Create order via AJAX
        fetch('/websitebatminton/api/create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear cart and redirect to success page
                fetch('/websitebatminton/api/clear-cart', { method: 'POST' })
                    .then(() => {
                        window.location.href = '/websitebatminton/order/' + data.order_id;
                    });
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại');
        });
    });
</script>
<?php include isset($layout) ? '' : ROOT_PATH . '/resources/views/layouts/footer.php'; ?>
