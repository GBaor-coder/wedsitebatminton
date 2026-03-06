<?php
/**
 * Auth Controller
 * Handles admin login/logout
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/User.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->userModel = new User();
    }
    
    /**
     * Show login form
     */
    public function login() {
        // If already logged in, redirect to dashboard
        $session = new Session();
        if ($session->isLoggedIn() && $session->hasRole('admin')) {
            $this->redirect('/websitebatminton/admin/dashboard');
        }
        
        // Generate CSRF token
        CSRF::generate();
        
        // Get flash messages
        $error = $session->flash('error');
        $success = $session->flash('success');
        
        $data = [
            'csrf_token' => CSRF::token(),
            'title' => 'Đăng nhập Admin',
            'error' => $error,
            'success' => $success
        ];
        
        extract($data);
        include '../resources/views/admin/auth/login.php';
    }
    
    /**
     * Handle login POST
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/websitebatminton/admin/login');
        }
        
        // Validate CSRF token
        $token = $_POST['csrf_token'] ?? '';
        if (!CSRF::validate($token)) {
            $session = new Session();
            $session->flash('error', 'Token không hợp lệ');
            $this->redirect('/websitebatminton/admin/login');
        }
        
        // Validate input
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $session = new Session();
            $session->flash('error', 'Vui lòng nhập email và mật khẩu');
            $this->redirect('/websitebatminton/admin/login');
        }
        
        // Verify credentials
        $user = $this->userModel->verifyPassword($email, $password);
        
        if (!$user) {
            $session = new Session();
            $session->flash('error', 'Email hoặc mật khẩu không đúng');
            $this->redirect('/websitebatminton/admin/login');
        }
        
        // Check if user is admin
        if ($user['role'] !== 'admin') {
            $session = new Session();
            $session->flash('error', 'Bạn không có quyền truy cập trang quản trị');
            $this->redirect('/websitebatminton/admin/login');
        }
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            $session = new Session();
            $session->flash('error', 'Tài khoản của bạn đã bị vô hiệu hóa');
            $this->redirect('/websitebatminton/admin/login');
        }
        
        // Set session
        $session = new Session();
        $session->set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);
        
        $session->flash('success', 'Chào mừng ' . $user['name'] . '!');
        $this->redirect('/websitebatminton/admin/dashboard');
    }
    
    /**
     * Logout
     */
    public function logout() {
        $session = new Session();
        $session->destroy();
        
        $session->flash('success', 'Đăng xuất thành công');
        $this->redirect('/websitebatminton/admin/login');
    }
}

