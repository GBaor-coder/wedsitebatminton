

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tin nhắn khách hàng</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th style="width: 60px">#</th>
                            <th>Họ tên</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Chủ đề</th>
                            <th>Ngày gửi</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = \Database::getInstance();
                        $contacts = $db->fetchAll("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 50");
                        ?>
                        <?php if (empty($contacts)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox display-4 text-muted mb-3 d-block"></i>
                                    <h5>Chưa có tin nhắn</h5>
                                    <p class="text-muted">Khách hàng sẽ gửi tin nhắn qua form liên hệ</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($contacts as $index => $contact): ?>
                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($contact['name']); ?></strong>
                                </td>
                                <td>
                                    <a href="tel:<?= htmlspecialchars($contact['phone']); ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($contact['phone']); ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($contact['email'] ?: 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-info"><?= htmlspecialchars($contact['subject'] ?: 'Không có'); ?></span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($contact['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary copy-phone" data-phone="<?= htmlspecialchars($contact['phone']); ?>" title="Copy SĐT">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success call-phone" data-phone="<?= htmlspecialchars($contact['phone']); ?>" title="Gọi">
                                        <i class="bi bi-telephone"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="viewMessage('<?= htmlspecialchars($contact['message']); ?>')" title="Xem tin nhắn">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tin nhắn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="messageContent">
                <!-- Message here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewMessage(message) {
    document.getElementById('messageContent').innerHTML = message.replace(/\n/g, '<br>');
    new bootstrap.Modal(document.getElementById('messageModal')).show();
}

document.querySelectorAll('.copy-phone').forEach(btn => {
    btn.addEventListener('click', function() {
        navigator.clipboard.writeText(this.dataset.phone);
        const original = this.innerHTML;
        this.innerHTML = '<i class="bi bi-check-lg"></i>';
        setTimeout(() => this.innerHTML = original, 1000);
    });
});

document.querySelectorAll('.call-phone').forEach(btn => {
    btn.addEventListener('click', function() {
        window.location.href = 'tel:' + this.dataset.phone;
    });
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>

