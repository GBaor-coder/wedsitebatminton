<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<?php $session = new \Session(); ?>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                        <div>
                            <h2 class="fw-bold mb-1" style="color: #0d6efd;">Giỏ hàng</h2>
                            <small class="text-muted">Quản lý sản phẩm chọn mua</small>
                        </div>
                        <a href="/websitebatminton/cart" class="text-primary fw-semibold"><?= isset($cartItems) ? count($cartItems) : 0 ?> sản phẩm</a>
                    </div>
                    <?php if (empty($cartItems)): ?>
                        <div class="p-4 rounded-3 border border-info bg-light text-center">
                            <h5 class="mb-2">Giỏ hàng của bạn đang trống</h5>
                            <p class="text-muted mb-3">Mời bạn mua thêm sản phẩm <a href="/websitebatminton/products">tại đây</a>.</p>
                            <a href="/websitebatminton/products" class="btn btn-primary">Mua sắm ngay</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center" style="width: 120px;">Đơn giá</th>
                                        <th class="text-center" style="width: 160px;">Số lượng</th>
                                        <th class="text-center" style="width: 120px;">Thành tiền</th>
                                        <th class="text-center" style="width: 80px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr class="cart-item-row" data-id="<?= $item['id'] ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= $item['image'] ? '/websitebatminton/storage/uploads/' . $item['image'] : '/websitebatminton/assets/images/product.jpg' ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 70px; height:70px; object-fit: cover; border-radius:10px; margin-right: 12px;" />
                                                    <div>
                                                        <?php $itemSlug = trim($item['slug'] ?? ''); ?>
                                                        <a href="<?= $itemSlug ? '/websitebatminton/products/' . urlencode($itemSlug) : '/websitebatminton/product?id=' . urlencode($item['id']) ?>" class="text-dark fw-semibold"><?= htmlspecialchars($item['name'] ?? 'Sản phẩm') ?></a>
                                                        <br><small class="text-muted">ID: #<?= str_pad($item['id'] ?? 0,5,'0',STR_PAD_LEFT) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                <?php if (!empty($item['sale_price'])): ?>
                                                    <div class="text-danger fw-bold"><?= formatPrice($item['sale_price']) ?></div>
                                                    <small class="text-decoration-line-through text-muted"><?= formatPrice($item['price']) ?></small>
                                                <?php else: ?>
                                                    <div class="text-primary fw-bold"><?= formatPrice($item['price']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                <div class="input-group input-group-sm mx-auto" style="width: 140px;">
                                                    <button class="btn btn-outline-primary btn-sm qty-btn" data-action="decrease" data-id="<?= $item['id'] ?>">−</button>
                                                    <input type="number" class="form-control form-control-sm text-center qty-input" value="<?= $item['quantity'] ?>" min="1" data-id="<?= $item['id'] ?>" style="width: 70px;" />
                                                    <button class="btn btn-outline-primary btn-sm qty-btn" data-action="increase" data-id="<?= $item['id'] ?>">+</button>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle item-subtotal" style="font-weight: 600; color: #0d6efd;">
                                                <?= formatPrice((!empty($item['sale_price']) ? $item['sale_price'] : $item['price']) * $item['quantity']) ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-outline-danger btn-sm remove-cart-btn" data-id="<?= $item['id'] ?>" title="Xóa"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="/websitebatminton/products" class="btn btn-outline-primary">← Tiếp tục mua sắm</a>
                            <button class="btn btn-outline-danger" onclick="clearCart()">Xóa toàn bộ</button>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4 p-3 border rounded-3 bg-white">
                        <h5 class="fw-semibold mb-3">SẢN PHẨM ĐÃ XEM</h5>

                        <?php if (!empty($viewedItems)): ?>
                            <div class="row g-2">
                                <?php foreach ($viewedItems as $viewed): ?>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center border rounded-2 p-2" style="background: #f8f9ff;">
                                            <img src="<?= !empty($viewed['image']) ? '/websitebatminton/storage/uploads/' . $viewed['image'] : '/websitebatminton/assets/images/product.jpg' ?>" alt="<?= htmlspecialchars($viewed['name']) ?>" style="width: 60px; height:60px; object-fit: cover; border-radius: 8px; margin-right: 10px;" />
                                            <div class="flex-grow-1">
                                                <a href="/websitebatminton/products/<?= $viewed['slug'] ?? '' ?>" class="text-dark fw-semibold"><?= htmlspecialchars($viewed['name'] ?? 'Sản phẩm đã xem') ?></a>
                                                <div class="text-muted small">Giá: <?= formatPrice($viewed['sale_price'] ?? $viewed['price'] ?? 0) ?></div>
                                            </div>
                                            <span class="badge bg-primary">Đã xem</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-muted">Lịch sử xem sản phẩm sẽ hiển thị ở đây khi bạn xem sản phẩm.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between">
                        <span>Tổng tiền:</span>
                        <strong class="total-amount"><?= formatPrice($cartTotal ?? 0) ?></strong>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="invoiceCheck">
                        <label class="form-check-label" for="invoiceCheck">Xuất hóa đơn</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú đơn hàng</label>
                        <textarea class="form-control" rows="3" placeholder="Ghi chú"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Nhập mã giảm giá (nếu có)">
                    </div>
                    <?php if ($session->isLoggedIn()): ?>
                        <a href="/websitebatminton/checkout" class="btn btn-primary w-100 mb-2">THANH TOÁN NGAY</a>
                    <?php else: ?>
                        <a href="/websitebatminton/login" class="btn btn-primary w-100 mb-2">ĐĂNG NHẬP ĐỂ THANH TOÁN</a>
                        <div class="alert alert-info text-center mb-2">
                            <small>Vui lòng đăng nhập hoặc <a href="/websitebatminton/register">đăng ký</a> để tiếp tục thanh toán</small>
                        </div>
                    <?php endif; ?>
                    <div class="text-center">
                        <a href="/websitebatminton/products" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Tiếp tục mua hàng</a>
                    </div>
                    <hr>
                    <p class="text-muted small mb-1"><i class="bi bi-shield-check me-2"></i>Bảo hành chính hãng</p>
                    <p class="text-muted small mb-1"><i class="bi bi-truck me-2"></i>Giao hàng toàn quốc</p>
                    <p class="text-muted small"><i class="bi bi-arrow-counterclockwise me-2"></i>Đổi trả 7 ngày</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let cartTotal = <?= isset($cartTotal) ? $cartTotal : 0 ?>;

function updateCart(id, qty) {
    fetch('/websitebatminton/cart/update', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}&quantity=${qty}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`.cart-item-row[data-id="${id}"]`);
            const subtotalCell = row ? row.querySelector('.item-subtotal') : null;

            if (subtotalCell && data.subtotal) {
                subtotalCell.textContent = data.subtotal;
            }

            const totalAmount = document.querySelector('.total-amount');
            if (totalAmount && data.cartTotal) {
                totalAmount.textContent = data.cartTotal;
            }

            if (qty <= 0 && row) {
                row.remove();
            }

            // nếu không còn sản phẩm, reload để hiển thị “Giỏ hàng trống”
            if (document.querySelectorAll('tbody tr').length === 0) {
                location.reload();
            }
        }
    })
    .catch(err => {
        console.error('Lỗi cập nhật giỏ hàng:', err);
    });
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('qty-btn')) {
        const target = e.target;
        const id = target.dataset.id;
        const input = document.querySelector(`.qty-input[data-id="${id}"]`);
        if (!input) return;
        let qty = parseInt(input.value);
        if (target.dataset.action === 'increase') qty++;
        else qty = Math.max(1, qty - 1);
        input.value = qty;
        updateCart(id, qty);
    }

    if (e.target.classList.contains('remove-cart-btn')) {
        const id = e.target.dataset.id;
        if (!id) return;
        if (confirm('Xóa sản phẩm này khỏi giỏ hàng?')) {
            fetch('/websitebatminton/cart/remove', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${id}`
            }).then(() => location.reload());
        }
    }
});

Array.from(document.querySelectorAll('.qty-input')).forEach(input => {
    input.addEventListener('change', function() {
        const id = this.dataset.id;
        const qty = parseInt(this.value);
        if (id && qty > 0) updateCart(id, qty);
    });
});

function clearCart() {
    if (confirm('Xóa toàn bộ giỏ hàng?')) {
        fetch('/websitebatminton/cart/clear', { method: 'POST' }).then(() => location.reload());
    }
}

function applyPromo() {
    alert('Tính năng mã giảm giá sẽ được thêm sau');
}
</script>



<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>
