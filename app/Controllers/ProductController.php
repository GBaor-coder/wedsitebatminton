<?php
/**
 * Product Controller
 * Handles product CRUD operations
 */

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Category.php';

class ProductController extends AdminBaseController {
    private $productModel;
    private $categoryModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    /**
     * List all products with pagination and search
     */
    public function index() {
        $search = $this->getSearch();
        $page = $this->getPage();
        $perPage = 10;
        
        $categoryId = $_GET['category'] ?? null;
        
        // Get products
        $products = $this->productModel->getPaginated($page, $perPage, $search, $categoryId);
        $total = $this->productModel->countProducts($search, $categoryId);
        
        // Get categories for filter
        $categories = $this->categoryModel->getActiveCategories();
        
        // Pagination
        $pagination = $this->paginate($total, $perPage);
        
        $data = [
            'title' => 'Quản lý sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'selected_category' => $categoryId,
            'pagination' => $pagination
        ];
        
        $this->render('products/index', $data);
    }
    
    /**
     * Show create product form
     */
    public function create() {
        $categories = $this->categoryModel->getActiveCategories();
        
        $data = [
            'title' => 'Thêm sản phẩm mới',
            'categories' => $categories,
            'product' => null
        ];
        
        $this->render('products/form', $data);
    }
    
    /**
     * Store new product
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/products');
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/products/create');
        }
        
        // Validate input
        $errors = $this->validateProduct($_POST);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/products/create');
        }
        
        // Handle image upload
        $image = $this->uploadImage($_FILES['image'] ?? []);
        
        if (!$image && !empty($_FILES['image']['name'])) {
            $this->session->flash('error', 'Lỗi upload hình ảnh');
            $this->redirect('/websitebatminton/admin/products/create');
        }
        
        // Prepare data
        $data = [
            'name' => trim($_POST['name']),
            'slug' => $this->productModel->generateSlug($_POST['name']),
            'category_id' => (int)$_POST['category_id'],
            'description' => trim($_POST['description']),
            'price' => (float)$_POST['price'],
            'sale_price' => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
            'quantity' => (int)$_POST['quantity'],
            'image' => $image,
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured']) ? 1 : 0
        ];
        
        try {
            $this->productModel->create($data);
            $this->session->flash('success', 'Thêm sản phẩm thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi thêm sản phẩm: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/products');
    }
    
    /**
     * Show edit product form
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->session->flash('error', 'Sản phẩm không tồn tại');
            $this->redirect('/websitebatminton/admin/products');
        }
        
        $categories = $this->categoryModel->getActiveCategories();
        
        $data = [
            'title' => 'Chỉnh sửa sản phẩm',
            'categories' => $categories,
            'product' => $product
        ];
        
        $this->render('products/form', $data);
    }
    
    /**
     * Update product
     */
    public function update() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/products');
        }
        
        $id = $_POST['id'] ?? 0;
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/products/edit?id=' . $id);
        }
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->session->flash('error', 'Sản phẩm không tồn tại');
            $this->redirect('/websitebatminton/admin/products');
        }
        
        // Validate input
        $errors = $this->validateProduct($_POST);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/products/edit?id=' . $id);
        }
        
        // Handle image upload
        $image = $product['image'];
        
        if (!empty($_FILES['image']['name'])) {
            $newImage = $this->uploadImage($_FILES['image']);
            
            if ($newImage) {
                // Delete old image
                if ($image && file_exists('../storage/uploads/' . $image)) {
                    unlink('../storage/uploads/' . $image);
                }
                $image = $newImage;
            }
        }
        
        // Prepare data
        $data = [
            'name' => trim($_POST['name']),
            'slug' => $this->productModel->generateSlug($_POST['name']),
            'category_id' => (int)$_POST['category_id'],
            'description' => trim($_POST['description']),
            'price' => (float)$_POST['price'],
            'sale_price' => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
            'quantity' => (int)$_POST['quantity'],
            'image' => $image,
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured']) ? 1 : 0
        ];
        
        try {
            $this->productModel->update($id, $data);
            $this->session->flash('success', 'Cập nhật sản phẩm thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/products');
    }
    
    /**
     * Delete product
     */
    public function delete() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/products');
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/products');
        }
        
        $id = $_POST['id'] ?? 0;
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->session->flash('error', 'Sản phẩm không tồn tại');
            $this->redirect('/websitebatminton/admin/products');
        }
        
        try {
            // Delete image
            if ($product['image'] && file_exists('../storage/uploads/' . $product['image'])) {
                unlink('../storage/uploads/' . $product['image']);
            }
            
            $this->productModel->delete($id);
            $this->session->flash('success', 'Xóa sản phẩm thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi xóa sản phẩm');
        }
        
        $this->redirect('/websitebatminton/admin/products');
    }
    
    /**
     * Update product status (AJAX)
     */
    public function status() {
        if (!$this->isPost()) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request']);
        }
        
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        
        try {
            $this->productModel->updateStatus($id, $status);
            $this->jsonResponse(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái']);
        }
    }
    
    /**
     * Validate product input
     */
    private function validateProduct($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Tên sản phẩm không được để trống';
        }
        
        if (empty($data['category_id'])) {
            $errors[] = 'Vui lòng chọn danh mục';
        }
        
        if (empty($data['price']) || $data['price'] <= 0) {
            $errors[] = 'Giá sản phẩm không hợp lệ';
        }
        
        if (!empty($data['sale_price']) && $data['sale_price'] > $data['price']) {
            $errors[] = 'Giá khuyến mãi phải nhỏ hơn giá gốc';
        }
        
        if (!isset($data['quantity']) || $data['quantity'] < 0) {
            $errors[] = 'Số lượng sản phẩm không hợp lệ';
        }
        
        return $errors;
    }
    
    /**
     * Upload product image
     */
    private function uploadImage($file) {
        if (empty($file['name'])) {
            return null;
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return null;
        }
        
        // Validate file size (max 2MB)
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return null;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_') . '.' . $extension;
        
        // Create uploads directory if not exists
        $uploadDir = '../storage/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return $filename;
        }
        
        return null;
    }
}

