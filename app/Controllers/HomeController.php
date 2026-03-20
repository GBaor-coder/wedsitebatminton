<?php
/**
 * Home Controller
 * Handles the homepage and public pages
 */

require_once ROOT_PATH . '/app/Models/Product.php';
require_once ROOT_PATH . '/app/Models/Category.php';
require_once ROOT_PATH . '/app/Models/Post.php';

class HomeController {
    private $productModel;
    private $categoryModel;
    private $postModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->postModel = new Post();
    }
    
    /**
     * Index - Homepage
     */
    public function index() {
        $db = \Database::getInstance();
        
        // Get 4 latest products (hot selling)
        $hotProducts = $db->fetchAll(
            "SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC LIMIT 4"
        );
        
        // Get all active categories
        $categories = $db->fetchAll(
            "SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC"
        );
        
        // Get 3 latest posts/news
        $latestPosts = $db->fetchAll(
            "SELECT * FROM posts WHERE status = 'active' ORDER BY created_at DESC LIMIT 3"
        );
        
        // Get featured products for display
        $featuredProducts = $db->fetchAll(
            "SELECT * FROM products WHERE featured = 1 AND status = 'active' ORDER BY created_at DESC LIMIT 8"
        );
        
        // Pass data to view
        $data = [
            'hotProducts' => $hotProducts,
            'categories' => $categories,
            'latestPosts' => $latestPosts,
            'featuredProducts' => $featuredProducts
        ];
        
        $this->view('home', $data);
    }
    
    /**
     * View - Render a view file
     */
    private function view($view, $data = []) {
        extract($data);
        
        $viewFile = ROOT_PATH . '/resources/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View not found: " . $view;
        }
    }
    
    /**
     * Products - Product listing page
     */
    public function products() {
        $categoryId = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 12;
        
        // Get products with pagination
        $products = $this->productModel->getPaginated($page, $perPage, $search, $categoryId);
        $totalProducts = $this->productModel->countProducts($search, $categoryId);
        $totalPages = ceil($totalProducts / $perPage);
        
        // Get categories
        $categories = $this->categoryModel->getActiveCategories();
        
        $data = [
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'categoryId' => $categoryId
        ];
        
        $this->view('products', $data);
    }
    
    /**
     * Product Detail - Single product page
     */
    public function productDetail() {
        $slug = $_GET['slug'] ?? '';
        
        $product = $this->productModel->findBySlug($slug);
        
        if (!$product) {
            echo "Product not found";
            return;
        }
        
        // Get related products
        $relatedProducts = $this->productModel->getByCategory($product['category_id'], 4);
        
        $data = [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];
        
        $this->view('product-detail', $data);
    }
    
    /**
     * Category - Products by category
     */
    public function category() {
        $slug = $_GET['slug'] ?? '';
        
        $category = $this->categoryModel->findBySlug($slug);
        
        if (!$category) {
            echo "Category not found";
            return;
        }
        
        $products = $this->productModel->getByCategory($category['id'], 20);
        
        $data = [
            'category' => $category,
            'products' => $products
        ];
        
        $this->view('category', $data);
    }
    
    /**
     * News - Blog/News listing
     */
    public function news() {
        $page = $_GET['page'] ?? 1;
        $perPage = 6;
        
        $posts = $this->postModel->getPaginated($page, $perPage);
        $totalPosts = $this->postModel->countPosts();
        $totalPages = ceil($totalPosts / $perPage);
        
        $data = [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];
        
        $this->view('news', $data);
    }
    
    /**
     * News Detail - Single post page
     */
    public function newsDetail() {
        $slug = $_GET['slug'] ?? '';
        
        $post = $this->postModel->findBySlug($slug);
        
        if (!$post) {
            echo "Post not found";
            return;
        }
        
        $data = [
            'post' => $post
        ];
        
        $this->view('news-detail', $data);
    }
    
    /**
     * Contact - Contact page
     */
    public function contact() {
        $this->view('contact');
    }
    
    /**
     * Contact Send - Handle contact form
     */
    public function contactSend() {
        // Basic validation
        if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['message'])) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: /websitebatminton/contact');
            exit;
        }
        
        // Save to DB or send email (placeholder)
        $db = \Database::getInstance();
        $data = [
            'name' => $_POST['name'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db = \Database::getInstance();
        $data = [
            'name' => $_POST['name'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'],
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db->query(
            "INSERT INTO contacts (name, phone, email, subject, message, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'],
                $data['phone'],
                $data['email'],
                $data['subject'],
                $data['message'],
                $data['ip_address'],
                $data['user_agent'],
                $data['created_at']
            ]
        );
        $_SESSION['success'] = 'Gửi thành công! Chúng tôi sẽ liên hệ sớm.';
        header('Location: /websitebatminton/contact/success');
        exit;
    }
    
    /**
     * Contact Success - Success page after form submission
     */
    public function contactSuccess() {
        $this->view('contact/success');
    }
    
    /**
     * About - About page
     */
    public function about() {
        $this->view('about');
    }
    
    /**
     * Admin Contacts - List contact messages
     */
    public function adminContacts() {
        $db = \Database::getInstance();
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $where = "1=1";
        $params = [];
        
        if ($search) {
            $where .= " AND (name LIKE ? OR phone LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
            $s = "%$search%";
            $params = array_fill(0, 5, $s);
        }
        
        $sql = "SELECT * FROM contacts WHERE $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
        $contacts = $db->fetchAll($sql, $params);
        
        $countSql = "SELECT COUNT(*) as total FROM contacts WHERE $where";
        $total = $db->fetchOne($countSql, $params)['total'] ?? 0;
        $totalPages = ceil($total / $perPage);
        
        $data = [
            'title' => 'Tin nhắn khách hàng',
            'contacts' => $contacts,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ];
        
        $this->render('admin/contacts/index', $data);
    }
    
    /**
     * Guide - Guide/How-to page
     */
    public function guide() {
        $this->view('guide');
    }
}


