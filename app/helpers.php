<?php
/**
 * Helper Functions
 * Utility functions for the application
 */

/**
 * Generate slug from string
 */
function slug($string) {
    $string = preg_replace('/[áàảãạăằẳẵắặâầẩẫấậ]/u', 'a', $string);
    $string = preg_replace('/[éèẻẽẹêềểễếệ]/u', 'e', $string);
    $string = preg_replace('/[íìỉĩị]/u', 'i', $string);
    $string = preg_replace('/[óòỏõọôồổỗốộơờởỡớợ]/u', 'o', $string);
    $string = preg_replace('/[úùủũụưừửữứự]/u', 'u', $string);
    $string = preg_replace('/[ýỳỷỹỵ]/u', 'y', $string);
    $string = preg_replace('/đ/u', 'd', $string);
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/u', '', $string);
    $string = preg_replace('/[\s-]+/u', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Format price
 */
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

/**
 * Get status badge class
 */
function getStatusBadge($status) {
    $classes = [
        'active' => 'success',
        'inactive' => 'secondary',
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
        'paid' => 'success',
        'failed' => 'danger'
    ];
    return $classes[$status] ?? 'secondary';
}

/**
 * Get status text
 */
function getStatusText($status) {
    $texts = [
        'active' => 'Hoạt động',
        'inactive' => 'Ẩn',
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
        'paid' => 'Đã thanh toán',
        'failed' => 'Thất bại'
    ];
    return $texts[$status] ?? $status;
}

/**
 * Upload image
 */
function uploadImage($file, $destination, $maxSize = 2097152, $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Lỗi tải file'];
    }

    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Kích thước file quá lớn (max 2MB)'];
    }

    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Chỉ chấp nhận file JPG, PNG'];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $targetPath = $destination . $filename;

    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $filename];
    }

    return ['success' => false, 'message' => 'Lỗi khi tải file'];
}

/**
 * Delete image
 */
function deleteImage($path) {
    if (file_exists($path)) {
        return unlink($path);
    }
    return false;
}

/**
 * Generate random string
 */
function generateRandomString($length = 10) {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

/**
 * Generate order number
 */
function generateOrderNumber() {
    return 'ORD-' . date('ym') . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
 * Sanitize input
 */
function sanitize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Flash message helper
 */
function flash($key, $value = null) {
    if ($value === null) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
    $_SESSION['flash'][$key] = $value;
}

/**
 * Check if current user is admin
 */
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Get current user
 */
function currentUser() {
    return $_SESSION['user'] ?? null;
}

