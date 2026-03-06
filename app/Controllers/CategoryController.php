<?php
/**
 * Category Controller
 * Handles category CRUD operations
 */

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Product.php';

class CategoryController extends AdminBaseController {
    private $categoryModel;
    private $productModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        
        $this->categoryModel = new Category();
        $this->productModel = new Product();
    }
    
    /**
     * List all categories
     */
    public function index() {
        $search = $this->getSearch();
        $page = $this->getPage();
        $perPage = 10;
        
        // Get categories
        $categories = $this->categoryModel->getPaginated($page, $perPage, $search);
        $total = $this->categoryModel->countCategories($search);
        
        // Pagination
        $pagination = $this->paginate($total, $perPage);
        
        // Get product count for each category
        foreach ($categories as &$category) {
            $category['product_count'] = $this->productModel->countProducts('', $category['id']);
        }
        
        $data = [
            'title' => 'Quản lý danh mục',
            'categories' => $categories,
            'search' => $search,
            'pagination' => $pagination
        ];
        
        $this->render('categories/index', $data);
    }
    
    /**
     * Show create category form
     */
    public function create() {
        $data = [
            'title' => 'Thêm danh mục mới',
            'category' => null
        ];
        
        $this->render('categories/form', $data);
    }
    
    /**
     * Store new category
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/categories/create');
        }
        
        // Validate input
        $errors = $this->validateCategory($_POST);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/categories/create');
        }
        
        // Prepare data
        $data = [
            'name' => trim($_POST['name']),
            'slug' => $this->categoryModel->generateSlug($_POST['name']),
            'description' => trim($_POST['description']),
            'status' => $_POST['status'] ?? 'active',
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null
        ];
        
        try {
            $this->categoryModel->create($data);
            $this->session->flash('success', 'Thêm danh mục thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi thêm danh mục: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/categories');
    }
    
    /**
     * Show edit category form
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            $this->session->flash('error', 'Danh mục không tồn tại');
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        $data = [
            'title' => 'Chỉnh sửa danh mục',
            'category' => $category
        ];
        
        $this->render('categories/form', $data);
    }
    
    /**
     * Update category
     */
    public function update() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        $id = $_POST['id'] ?? 0;
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/categories/edit?id=' . $id);
        }
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            $this->session->flash('error', 'Danh mục không tồn tại');
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        // Validate input
        $errors = $this->validateCategory($_POST);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/categories/edit?id=' . $id);
        }
        
        // Prepare data
        $data = [
            'name' => trim($_POST['name']),
            'slug' => $this->categoryModel->generateSlug($_POST['name']),
            'description' => trim($_POST['description']),
            'status' => $_POST['status'] ?? 'active',
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null
        ];
        
        try {
            $this->categoryModel->update($id, $data);
            $this->session->flash('success', 'Cập nhật danh mục thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi cập nhật danh mục: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/categories');
    }
    
    /**
     * Delete category
     */
    public function delete() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        $id = $_POST['id'] ?? 0;
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            $this->session->flash('error', 'Danh mục không tồn tại');
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        // Check if category has products
        $productCount = $this->productModel->countProducts('', $id);
        
        if ($productCount > 0) {
            $this->session->flash('error', 'Không thể xóa danh mục đang có sản phẩm');
            $this->redirect('/websitebatminton/admin/categories');
        }
        
        try {
            $this->categoryModel->delete($id);
            $this->session->flash('success', 'Xóa danh mục thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi xóa danh mục');
        }
        
        $this->redirect('/websitebatminton/admin/categories');
    }
    
    /**
     * Validate category input
     */
    private function validateCategory($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Tên danh mục không được để trống';
        }
        
        return $errors;
    }
}

