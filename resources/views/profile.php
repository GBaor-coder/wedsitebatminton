<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="p-4 rounded-4" style="background: #f3f6ff;">
                <div class="row g-4">
                    <!-- Sidebar Profile -->
                    <div class="col-md-4">
                        <div class="p-4 rounded-4 bg-white shadow-sm h-100">
                            <div class="text-center mb-4">
                                <div class="mx-auto" style="width: 90px; height: 90px; border-radius: 50%; background: #dbe4ff;
                                    display: flex; align-items: center; justify-content: center; font-size: 32px; color: #1c42f3;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <h4 class="mt-3 mb-1 fw-bold"><?= htmlspecialchars($profile['name']) ?></h4>
                                <small class="text-muted"><?= htmlspecialchars($profile['email']) ?></small>
                            </div>

                            <?php $session = new \Session(); $isAdmin = ($session->isLoggedIn() && ($session->user()['role'] ?? '') === 'admin'); ?>
                            <div class="list-group">
                                <a href="/websitebatminton/thanh-vien" class="list-group-item list-group-item-action active">Thông tin cá nhân</a>
                                <a href="<?= $isAdmin ? '/websitebatminton/admin/dashboard' : '/websitebatminton/profile/addresses' ?>" class="list-group-item list-group-item-action"><?= $isAdmin ? 'Quản lý dashboard' : 'Địa chỉ giao hàng' ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <div class="col-md-8">
                        <div class="p-4 rounded-4 bg-white shadow-sm h-100">
                            <h3 class="fw-bold mb-4">Thông tin tài khoản</h3>

                            <?php if (!empty($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="/websitebatminton/profile/update" method="POST" class="row g-3">
                                <?php echo \CSRF::field(); ?>
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($profile['name']) ?>" placeholder="Họ và tên">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Giới tính</label>
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" <?= ($profile['gender'] === 'male') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="genderMale">Nam</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female" <?= ($profile['gender'] === 'female') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="genderFemale">Nữ</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($profile['address']) ?>" placeholder="Nhập địa chỉ">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Quốc gia</label>
                                    <input type="text" class="form-control" name="country" value="<?= htmlspecialchars($profile['country']) ?>" placeholder="Quốc gia">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tỉnh thành</label>
                                    <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($profile['city']) ?>" placeholder="Tỉnh thành">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($profile['email']) ?>" placeholder="Email">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($profile['phone']) ?>" placeholder="Số điện thoại">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" name="birthdate" value="<?= htmlspecialchars($profile['birthdate']) ?>">
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item.active { background: #1c42f3; border-color: #1c42f3; color: #fff; }
    .list-group-item { border-radius: 12px; margin-bottom: 8px; }
</style>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>