

<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="text-center p-5 shadow-lg rounded-4 success-card">
                <!-- Success Icon -->
                <div class="success-icon mb-4">
                    <div class="check-circle">
                        <i class="bi bi-check-circle-fill display-1 text-success"></i>
                    </div>
                </div>
                
                <h1 class="display-4 fw-bold text-success mb-4 lh-1">
                    Gửi thành công!
                </h1>
                
                <p class="lead text-muted mb-5">
                    Cảm ơn bạn đã liên hệ với <strong class="text-primary">JP SPORT</strong>. 
                    Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong vòng 24h.
                </p>
                
                <!-- Contact Info Reminder -->
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="contact-item text-center p-4 border rounded-3">
                            <i class="bi bi-telephone-fill display-6 text-success mb-3 d-block"></i>
                            <h5>Gọi ngay</h5>
                            <a href="tel:0342826430" class="btn btn-success btn-lg w-100">
                                0342 826 430
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-item text-center p-4 border rounded-3">
                            <i class="bi bi-chat-dots-fill display-6 text-primary mb-3 d-block"></i>
                            <h6>Zalo</h6>
                            <a href="https://zalo.me/0342826430" class="btn btn-primary btn-lg w-100">
                                Chat Zalo
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-item text-center p-4 border rounded-3">
                            <i class="bi bi-facebook display-6 text-primary mb-3 d-block"></i>
                            <h6>Messenger</h6>
                            <a href="https://m.me/BAORSTACK.VN" class="btn btn-info btn-lg w-100">
                                Nhắn Messenger
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                <div class="d-grid gap-3 col-lg-6 mx-auto">
                    <a href="/websitebatminton/" class="btn btn-primary btn-lg">
                        <i class="bi bi-house-door me-2"></i>
                        Về trang chủ
                    </a>
                    <a href="/websitebatminton/contact" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-chat-square-text me-2"></i>
                        Gửi thêm tin nhắn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-card {
    background: linear-gradient(135deg, #f8fff8 0%, white 100%);
    border: 1px solid rgba(25, 135, 84, 0.15);
    max-width: 700px;
    margin: 0 auto;
}

.check-circle {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(25, 135, 84, 0.2);
}

.contact-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.contact-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .success-card {
        margin: 0 15px;
        padding: 2rem !important;
    }
}
</style>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>

