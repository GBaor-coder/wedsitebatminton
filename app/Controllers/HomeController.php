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
     * About - About page
     */
    public function about() {
        $this->view('about');
    }
    
    /**
     * Guide - Guide/How-to page
     */
    public function guide() {
        $this->view('guide');
    }
}

