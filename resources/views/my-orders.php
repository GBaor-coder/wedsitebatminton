<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<div class="profile-page py-5" style="background: linear-gradient(180deg, #eef4ff 0%, #f8fbff 100%); min-height: calc(100vh - 140px);">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-md-3">
                <?php include ROOT_PATH . '/resources/views/partials/sidebar-profile.php'; ?>
            </div>
            
            <!-- Content -->
            <div class="col-md-9">
                <div class="profile-card rounded-4 shadow-sm border-0 bg-white h-100">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold mb-0">Đơn hàng của bạn</h3>
                            <a href="/websitebatminton/products" class="btn btn-outline-primary rounded-pill px-4">
                                <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
                            </a>
                        </div>

                        <?php if (empty($orders)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-bag-check display-1 text-muted mb-4 opacity-50"></i>
                                <h4 class="text-muted mb-3">Chưa có đơn hàng</h4>
                                <p class="text-muted mb-4">Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
                                <a href="/websitebatminton/products" class="btn btn-primary btn-lg rounded-pill px-5">
                                    <i class="bi bi-arrow-right me-2"></i>Bắt đầu mua sắm
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-bold">Mã đơn hàng</th>
                                            <th class="fw-bold">Ngày đặt</th>
                                            <th class="fw-bold">Tổng tiền</th>
                                            <th class="fw-bold">Trạng thái</th>
                                            <th class="fw-bold text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <?php
                                            $statusClass = 'bg-secondary';
                                            $statusText = $order['status'];
                                            
                                            switch($order['status']) {
                                                case 'pending':
                                                    $statusClass = 'bg-warning text-dark';
                                                    $statusText = 'Chờ xử lý';
                                                    break;
                                                case 'processing':
                                                    $statusClass = 'bg-info';
                                                    $statusText = 'Đang xử lý';
                                                    break;
                                                case 'shipped':
                                                    $statusClass = 'bg-primary';
                                                    $statusText = 'Đã gửi hàng';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'Hoàn thành';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-danger';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                            }
                                            ?>
                                            <tr class="hover-row">
                                                <td>
                                                    <strong class="text-primary">#<?= htmlspecialchars($order['order_number']) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="text-muted"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                                                </td>
                                                <td>
                                                    <strong class="text-success"><?= number_format($order['total_amount'], 0, ',', '.') ?>₫</strong>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $statusClass ?> px-3 py-2 fw-medium"><?= $statusText ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="/websitebatminton/order/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                        <i class="bi bi-eye me-1"></i>Chi tiết
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                                <nav class="mt-4">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                            <li class="page-item <?= $pagination['page'] == $i ? 'active' : '' ?>">
                                                <a class="page-link rounded-pill" href="?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-page { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
.profile-card { border-radius: 24px !important; box-shadow: 0 25px 50px rgba(43, 60, 255, 0.1); }
.table th { border-top: none; font-size: 0.95rem; letter-spacing: 0.5px; }
.table td { vertical-align: middle; border-color: #f8f9fa; }
.hover-row:hover { background-color: rgba(43, 60, 255, 0.03); }
.btn-outline-primary:hover { background: linear-gradient(135deg, #2b3cff, #1e90ff); border-color: #2b3cff; color: white; }
.pagination .page-link { border: 1px solid #e9ecef; margin: 0 2px; }
.pagination .page-item.active .page-link { background: linear-gradient(135deg, #2b3cff, #1e90ff); border-color: #2b3cff; }
@media (max-width: 768px) {
    .profile-card .card-body { padding: 2rem 1.5rem !important; }
    .table { font-size: 0.875rem; }
    .table-responsive { border-radius: 16px; overflow: hidden; }
}
</style>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>

