<?php
/**
 * User Controller
 * Handles user management
 */

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../Models/User.php';

class UserController extends AdminBaseController {
    private $userModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        
        $this->userModel = new User();
    }
    
    /**
     * List all users
     */
    public function index() {
        $search = $this->getSearch();
        $page = $this->getPage();
        $perPage = 10;
        
        // Get users
        $users = $this->userModel->getPaginated($page, $perPage, $search);
        $total = $this->userModel->countUsers($search);
        
        // Pagination
        $pagination = $this->paginate($total, $perPage);
        
        $data = [
            'title' => 'Quản lý người dùng',
            'users' => $users,
            'search' => $search,
            'pagination' => $pagination
        ];
        
        $this->render('users/index', $data);
    }
    
    /**
     * Show create user form
     */
    public function create() {
        $data = [
            'title' => 'Thêm người dùng mới',
            'user' => null
        ];
        
        $this->render('users/form', $data);
    }
    
    /**
     * Store new user
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/users');
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/users/create');
        }
        
        // Validate input
        $errors = $this->validateUser($_POST);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/users/create');
        }
        
        // Check if email already exists
        $existingUser = $this->userModel->findByEmail($_POST['email']);
        
        if ($existingUser) {
            $this->session->flash('error', 'Email đã được sử dụng');
            $this->redirect('/websitebatminton/admin/users/create');
        }
        
        // Prepare data
        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'role' => $_POST['role'] ?? 'user',
            'status' => $_POST['status'] ?? 'active'
        ];
        
        try {
            $this->userModel->createUser($data);
            $this->session->flash('success', 'Thêm người dùng thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi thêm người dùng: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/users');
    }
    
    /**
     * Show edit user form
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->session->flash('error', 'Người dùng không tồn tại');
            $this->redirect('/websitebatminton/admin/users');
        }
        
        // Don't show password
        unset($user['password']);
        
        $data = [
            'title' => 'Chỉnh sửa người dùng',
            'user' => $user
        ];
        
        $this->render('users/form', $data);
    }
    
    /**
     * Update user
     */
    public function update() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/users');
        }
        
        $id = $_POST['id'] ?? 0;
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/users/edit?id=' . $id);
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->session->flash('error', 'Người dùng không tồn tại');
            $this->redirect('/websitebatminton/admin/users');
        }
        
        // Validate input
        $errors = $this->validateUser($_POST, true);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/users/edit?id=' . $id);
        }
        
        // Check if email already exists (excluding current user)
        $existingUser = $this->userModel->findByEmail($_POST['email']);
        
        if ($existingUser && $existingUser['id'] != $id) {
            $this->session->flash('error', 'Email đã được sử dụng');
            $this->redirect('/websitebatminton/admin/users/edit?id=' . $id);
        }
        
        // Prepare data
        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'role' => $_POST['role'] ?? 'user',
            'status' => $_POST['status'] ?? 'active'
        ];
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }
        
        try {
            $this->userModel->updateUser($id, $data);
            $this->session->flash('success', 'Cập nhật người dùng thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi cập nhật người dùng: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/users');
    }
    
    /**
     * Delete user
     */
    public function delete() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/users');
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/users');
        }
        
        $id = $_POST['id'] ?? 0;
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $this->session->flash('error', 'Người dùng không tồn tại');
            $this->redirect('/websitebatminton/admin/users');
        }
        
        // Prevent deleting self
        if ($id == $this->adminUser['id']) {
            $this->session->flash('error', 'Bạn không thể xóa tài khoản của chính mình');
            $this->redirect('/websitebatminton/admin/users');
        }
        
        try {
            $this->userModel->delete($id);
            $this->session->flash('success', 'Xóa người dùng thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi xóa người dùng');
        }
        
        $this->redirect('/websitebatminton/admin/users');
    }
    
    /**
     * Update user status (AJAX)
     */
    public function status() {
        if (!$this->isPost()) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request']);
        }
        
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        
        try {
            $this->userModel->updateStatus($id, $status);
            $this->jsonResponse(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái']);
        }
    }
    
    /**
     * Validate user input
     */
    private function validateUser($data, $isUpdate = false) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Tên không được để trống';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'Email không được để trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }
        
        if (!$isUpdate && empty($data['password'])) {
            $errors[] = 'Mật khẩu không được để trống';
        } elseif ($isUpdate && !empty($data['password']) && strlen($data['password']) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        
        if (!$isUpdate && strlen($data['password'] ?? '') < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        
        return $errors;
    }
}

