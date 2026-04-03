<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<div class="container-fluid py-5">
    <!-- Hero Section -->
    <div class="row text-center mb-5">
        <div class="col-12">
            <h1 class="display-4 fw-bold mb-3 text-primary">LIÊN HỆ JP SPORT</h1>
            <p class="lead">Liên hệ tư vấn miễn phí hoặc ghé cửa hàng trải nghiệm</p>
        </div>
    </div>

    <!-- Contact Row -->
    <div class="row g-5 mb-5">
        <!-- Contact Info -->
        <div class="col-lg-4">
            <div class="contact-info-card shadow-lg p-5 rounded-4 h-100">
                <div class="text-center mb-4">
                    <img src="assets\images\logo\JPTachnen.png" alt="JP Sport" style="width: 0 px; height: 96px; border-radius: 16px; object-fit: cover; border: 2px solid #0d6efd; box-shadow: 0 8px 20px rgba(0,0,0,0.1);" />
                    <p class="mt-3 mb-0 fw-semibold">JP SPORT - Hỗ trợ khách hàng</p>
                </div>
                <h3 class="text-primary mb-4">Thông tin liên hệ</h3>
                
                <!-- Address -->
                <div class="contact-item mb-4 d-flex align-items-start">
                    <i class="bi bi-geo-alt display-6 text-primary me-3 mt-1"></i>
                    <div>
                        <h5 class="fw-bold" style="color: black;">Cửa hàng chính</h5>
                        <a style="color: black;">Phường Cao Lãnh , Tỉnh Đồng Tháp</a>

                    </div>
                </div>

                <!-- Phone -->
                <div class="contact-item mb-4 d-flex align-items-start">
                    <i class="bi bi-telephone display-6 text-success me-3 mt-1"></i>
                    <div>
                        <h5 class="fw-bold" style="color: black;">Hotline</h5>
                        <a class="text-decoration-none " style="color: black;">0961625636 - 0342826430</a>
                        <br>
                        <a style="color: black;">08:00 - 21:00 hằng ngày</a>
                    </div>
                </div>

                <!-- Email -->
                <div class="contact-item mb-4 d-flex align-items-start">
                    <i class="bi bi-envelope display-6 text-info me-3 mt-1"></i>
                    <div>
                            <h5 class="fw-bold" style="color: black;">Email</h5>
                            <a  class="text-decoration-none" style="color: black;">dangngocthaibao.thd@gmail.com</a>
                    </div>
                </div>

                <!-- Social -->
                <div class="contact-social mt-4 text-center">
                    <h6 class="text-muted mb-3">Theo dõi chúng tôi</h6>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="social-btn fs-4 text-primary hover-grow"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn fs-4 text-danger hover-grow"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-btn fs-4 text-info hover-grow"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-btn fs-4 text-dark hover-grow"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="contact-form-card shadow-lg p-5 rounded-4">
                <h3 class="text-primary mb-4">Gửi tin nhắn</h3>
                <form method="POST" action="/websitebatminton/contact/send" class="needs-validation" novalidate>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Họ tên" required>
                                <label for="name">Họ tên *</label>
                                <div class="invalid-feedback">Vui lòng nhập họ tên</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="SĐT" required>
                                <label for="phone">Số điện thoại *</label>
                                <div class="invalid-feedback">Vui lòng nhập số điện thoại</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                <label for="email">Email (không bắt buộc)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select" id="subject" name="subject">
                                    <option value="">Chọn chủ đề</option>
                                    <option value="tư vấn vợt">Tư vấn vợt</option>
                                    <option value="tư vấn giày">Tư vấn giày</option>
                                    <option value="báo giá">Báo giá</option>
                                    <option value="giao hàng">Giao hàng</option>
                                    <option value="khác">Khác</option>
                                </select>
                                <label for="subject">Chủ đề</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-0">
                                <textarea class="form-control" id="message" name="message" placeholder="Nội dung" style="height: 140px" required></textarea>
                                <label for="message">Nội dung tin nhắn *</label>
                                <div class="invalid-feedback">Vui lòng viết nội dung</div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Gửi tin nhắn <i class="bi bi-send ms-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Store Locations -->
    <div class="row g-4 mb-5">
        <div class="col-12 text-center mb-5">
            <h3 class="h4 text-primary">Cửa hàng JP Sport</h3>
        </div>
        <div class="col-lg-6">
            <div class="store-card shadow-lg p-4 rounded-4 h-100">
                <h5 class="mb-3">🏪 Cửa hàng chính - TP.HCM</h5>
                <div class="mb-3">
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <strong>Phường Cao Lãnh Tỉnh Đồng Tháp</strong>
                </div>
                <div class="mb-3">
                    <i class="bi bi-clock me-2 text-success"></i>
                    08:00 - 21:00 (Thứ 2-CN)
                </div>
                <div class="mb-3">
                    <i class="bi bi-telephone me-2 text-info"></i>
                    <a href="tel:0342826430">0342 826 430</a>
                </div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.360947987!2d106.68!3d10.76!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTHCsDQ1JzM0LjIiTiAxMDbCsDQwJzI5LjUiRQ!5e0!3m2!1svi!2svn!4v1690000000000!5m2!1svi!2svn" 
                        width="100%" height="300" class="rounded-3 shadow" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="store-card shadow-lg p-4 rounded-4 h-100">
                <h5 class="mb-3">📱 Hỗ trợ online</h5>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="support-item text-center p-3 border rounded-3 hover-grow">
                            <i class="bi bi-facebook fs-1 text-primary mb-2 d-block"></i>
                            <h6>Facebook Messenger</h6>
                            <a href="https://m.me/BAORSTACK.VN" class="btn btn-outline-primary btn-sm w-100 mt-2">Nhắn tin</a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="support-item text-center p-3 border rounded-3 hover-grow">
                            <i class="bi bi-whatsapp fs-1 text-success mb-2 d-block"></i>
                            <h6>Zalo OA</h6>
                            <a href="https://zalo.me/0342826430" class="btn btn-success btn-sm w-100 mt-2">Chat Zalo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
}
.support-item {
    transition: all 0.3s ease;
    cursor: pointer;
}
.hover-grow:hover {
    transform: scale(1.05);
}
.contact-info-card {
    background: linear-gradient(135deg, #f8f9ff 0%, white 100%);
}
.contact-form-card {
    background: white;
}
.news-meta i {
    font-size: 0.875rem;
}
</style>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>
