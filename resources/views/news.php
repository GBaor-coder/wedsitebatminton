<?php require_once ROOT_PATH . '/resources/views/layouts/header.php'; ?>

<div class="container-fluid py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold mb-3 text-primary">TIN TỨC MỚI NHẤT</h1>
            <p class="lead">Cập nhật tin tức, chia sẻ kinh nghiệm chơi cầu lông</p>
        </div>
    </div>

    <!-- News Grid -->
    <?php if (!empty($posts)): ?>
    <div class="row g-4 mb-5">
        <?php foreach ($posts as $post): ?>
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm hover-lift">
                <!-- Image -->
                <div class="news-image overflow-hidden">
                    <img src="<?= $post['image'] ? '/websitebatminton/storage/uploads/' . $post['image'] : '/websitebatminton/assets/images/news-default.jpg'; ?>" 
                         alt="<?= htmlspecialchars($post['title']); ?>" class="card-img-top img-fluid">
                </div>
                
                <!-- Meta -->
                <div class="card-body p-0 pt-3">
                    <div class="news-meta small text-muted mb-2">
                        <span><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($post['created_at'])); ?></span>
                        <span class="ms-3"><i class="bi bi-eye me-1"></i><?= $post['views'] ?? 0; ?></span>
                    </div>
                    
                    <!-- Title -->
                    <h5 class="card-title mb-3 px-3">
                        <a href="/websitebatminton/news/<?= htmlspecialchars($post['slug']); ?>" class="text-dark text-decoration-none">
                            <?= htmlspecialchars($post['title']); ?>
                        </a>
                    </h5>
                    
                    <!-- Excerpt -->
                    <p class="card-text px-3 small text-muted mb-3">
                        <?= htmlspecialchars(substr($post['excerpt'] ?? $post['content'] ?? '', 0, 120)); ?>...
                    </p>
                    
                    <!-- Read more -->
                    <div class="px-3 pb-3">
                        <a href="/websitebatminton/news/<?= htmlspecialchars($post['slug']); ?>" class="btn btn-outline-primary btn-sm">
                            Đọc thêm <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <nav class="d-flex justify-content-center">
        <ul class="pagination">
            <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $currentPage - 1; ?>">Trước</a>
            </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $currentPage + 1; ?>">Sau</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php else: ?>
    <div class="row">
        <div class="col-12 text-center py-5">
            <i class="bi bi-newspaper display-1 text-muted mb-4"></i>
            <h3>Chưa có tin tức</h3>
            <p class="text-muted">Tin tức sẽ được cập nhật sớm...</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once ROOT_PATH . '/resources/views/layouts/footer.php'; ?>
