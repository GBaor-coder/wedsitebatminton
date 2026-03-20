

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-chat-dots me-2 text-primary"></i>
            Messenger - Tin nhắn khách hàng
        </h1>
        <a href="/websitebatminton/admin/contacts" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-chat-dots display-4 text-primary mb-3"></i>
                    <h3 class="h4 mb-1"><?php echo number_format($total_messages ?? 0); ?></h3>
                    <p class="text-muted mb-0">Tổng tin nhắn</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-clock-history display-4 text-warning mb-3"></i>
                    <h3 class="h4 mb-1"><?php echo number_format($today_messages ?? 0); ?></h3>
                    <p class="text-muted mb-0">Hôm nay</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-telephone display-4 text-success mb-3"></i>
                    <h3 class="h4 mb-1"><?php echo number_format($phone_count ?? 0); ?></h3>
                    <p class="text-muted mb-0">Có SĐT</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-envelope display-4 text-info mb-3"></i>
                    <h3 class="h4 mb-1"><?php echo number_format($unread_count ?? 0); ?></h3>
                    <p class="text-muted mb-0">Chưa đọc</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messenger Chat Interface -->
    <div class="row g-4">
        <!-- Contacts Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Danh sách tin nhắn
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="contacts-list" style="height: 600px; overflow-y: auto;">
                        <?php if (empty($contacts)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 mb-3 opacity-50"></i>
                                <p>Chưa có tin nhắn nào</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($contacts as $contact): ?>
                            <div class="contact-item p-3 border-bottom cursor-pointer" data-phone="<?= htmlspecialchars($contact['phone']); ?>" data-message="<?= htmlspecialchars($contact['message']); ?>">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 20px;">
                                            <?php echo strtoupper(substr($contact['name'], 0, 1)); ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($contact['name']); ?></h6>
                                            <small class="text-muted"><?= date('H:i', strtotime($contact['created_at'])); ?></small>
                                        </div>
                                        <p class="mb-1 small text-muted"><?= htmlspecialchars(substr($contact['message'], 0, 50)); ?>...</p>
                                        <?php if ($contact['email']): ?>
                                            <small class="text-info"><?= htmlspecialchars($contact['email']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex align-items-center">
                    <div class="user-avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        KH
                    </div>
                    <div>
                        <h6 class="mb-0" id="chat-name">Chọn tin nhắn để xem</h6>
                        <small class="text-muted" id="chat-phone">Số điện thoại</small>
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-sm btn-outline-primary call-btn" style="display: none;">
                            <i class="bi bi-telephone"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" style="display: none;">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    <div class="chat-messages flex-grow-1 p-4" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-chat-square-dots display-4 mb-3 opacity-50"></i>
                            <h5>Chọn một tin nhắn bên trái để bắt đầu chat</h5>
                            <p class="lead">Tin nhắn khách hàng sẽ hiển thị ở đây</p>
                        </div>
                    </div>
                    <div class="p-4 border-top bg-white">
                        <div class="input-group">
                            <textarea class="form-control" rows="2" placeholder="Nhập phản hồi..."></textarea>
                            <button class="btn btn-primary">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add to sidebar link too -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contact list click handler
    document.querySelectorAll('.contact-item').forEach(item => {
        item.addEventListener('click', function() {
            const phone = this.dataset.phone;
            const name = this.querySelector('h6').textContent;
            const message = this.dataset.message;
            
            // Update header
            document.getElementById('chat-name').textContent = name;
            document.getElementById('chat-phone').textContent = phone;
            
            // Show buttons
            document.querySelector('.call-btn').style.display = 'inline-block';
            document.querySelector('.copy-btn').style.display = 'inline-block';
            
            // Update messages
            document.querySelector('.chat-messages').innerHTML = `
                <div class="message received mb-3">
                    <div class="message-content p-3 bg-light rounded-3">
                        <p>${message.replace(/\n/g, '<br>')}</p>
                    </div>
                    <small class="text-muted">${new Date().toLocaleString('vi-VN')}</small>
                </div>
            `;
            
            // Scroll to bottom
            document.querySelector('.chat-messages').scrollTop = document.querySelector('.chat-messages').scrollHeight;
        });
    });
    
    // Call button
    document.querySelector('.call-btn').addEventListener('click', function() {
        const phone = document.getElementById('chat-phone').textContent;
        window.location.href = 'tel:' + phone.replace(/[^\d+]/g, '');
    });
    
    // Copy button
    document.querySelector('.copy-btn').addEventListener('click', function() {
        const phone = document.getElementById('chat-phone').textContent;
        navigator.clipboard.writeText(phone);
        const btn = this;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        setTimeout(() => btn.innerHTML = original, 2000);
    });
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>

