<?php
/**
 * Admin Base Controller
 * Base class for all admin controllers
 */

require_once __DIR__ . '/../Core/Controller.php';

class AdminBaseController extends Controller {
    protected $session;
    protected $adminUser;
    
    public function __construct($params = []) {
        parent::__construct($params);
        
        // Initialize session
        $this->session = new Session();
        
        // Check admin authentication
        $this->checkAuth();
        
        // Get admin user data
        $this->adminUser = $this->session->user();
        
        // Generate CSRF token
        CSRF::generate();
    }
    
    /**
     * Check if admin is logged in
     */
    protected function checkAuth() {
        if (!$this->session->isLoggedIn()) {
            $this->redirect('/websitebatminton/admin/login');
        }
        
        // Check admin role
        if (!$this->session->hasRole('admin')) {
            $this->session->flash('error', 'Bạn không có quyền truy cập trang quản trị');
            $this->redirect('/websitebatminton/admin/login');
        }
    }
    
    /**
     * Render admin layout
     */
    protected function render($view, $data = []) {
        $data['admin_user'] = $this->adminUser;
        $data['csrf_token'] = CSRF::token();
        
        // Get flash messages
        $data['success'] = $this->session->flash('success');
        $data['error'] = $this->session->flash('error');
        
        extract($data);
        
        // Include header
        include '../resources/views/admin/partials/header.php';
        
        // Include sidebar
        include '../resources/views/admin/partials/sidebar.php';
        
        // Include main content
        $viewFile = '../resources/views/admin/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View not found: " . $view;
        }
        
        // Include footer
        include '../resources/views/admin/partials/footer.php';
    }
    
    /**
     * JSON response for AJAX
     */
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        
        if (!CSRF::validate($token)) {
            $this->session->flash('error', 'Token không hợp lệ');
            return false;
        }
        
        return true;
    }
    
    /**
     * Get current page number
     */
    protected function getPage() {
        return (int)($_GET['page'] ?? 1);
    }
    
    /**
     * Get search keyword
     */
    protected function getSearch() {
        return trim($_GET['search'] ?? '');
    }
    
    /**
     * Paginate data
     */
    protected function paginate($total, $perPage = 10) {
        $page = $this->getPage();
        $totalPages = ceil($total / $perPage);
        
        return [
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'offset' => ($page - 1) * $perPage
        ];
    }
}

