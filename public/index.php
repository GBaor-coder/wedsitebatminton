<?php
/**
 * Front Controller - Main Entry Point
 * Handles all incoming requests
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/database.php';

// Load core classes
require_once ROOT_PATH . '/app/Core/Database.php';
require_once ROOT_PATH . '/app/Core/Session.php';
require_once ROOT_PATH . '/app/Core/CSRF.php';
require_once ROOT_PATH . '/app/Core/Controller.php';
require_once ROOT_PATH . '/app/Core/Router.php';

// Load helpers
require_once ROOT_PATH . '/app/helpers.php';

// Load Models
require_once ROOT_PATH . '/app/Models/Model.php';
require_once ROOT_PATH . '/app/Models/User.php';
require_once ROOT_PATH . '/app/Models/Category.php';
require_once ROOT_PATH . '/app/Models/Product.php';
require_once ROOT_PATH . '/app/Models/Order.php';
require_once ROOT_PATH . '/app/Models/Post.php';

// Load Controllers
require_once ROOT_PATH . '/app/Controllers/HomeController.php';

// Autoload function for additional controllers
spl_autoload_register(function ($class) {
    // Check if it's a controller
    if (strpos($class, 'Controller') !== false) {
        $file = ROOT_PATH . '/app/Controllers/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Get URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);

// Initialize router
$router = new Router();

// Define routes
// Public routes - Homepage (empty string and root)
$router->get('', ['controller' => 'Home', 'action' => 'index']);
$router->get('/', ['controller' => 'Home', 'action' => 'index']);

// Products
$router->get('products', ['controller' => 'Home', 'action' => 'products']);
$router->get('products/{slug}', ['controller' => 'Home', 'action' => 'productDetail']);
$router->get('categories/{slug}', ['controller' => 'Home', 'action' => 'category']);

// News
$router->get('news', ['controller' => 'Home', 'action' => 'news']);
$router->get('news/{slug}', ['controller' => 'Home', 'action' => 'newsDetail']);

// Static pages
$router->get('contact', ['controller' => 'Home', 'action' => 'contact']);
$router->get('about', ['controller' => 'Home', 'action' => 'about']);
$router->get('guide', ['controller' => 'Home', 'action' => 'guide']);

// Cart & Checkout
$router->get('cart', ['controller' => 'Home', 'action' => 'cart']);
$router->get('checkout', ['controller' => 'Home', 'action' => 'checkout']);

// Auth
$router->get('login', ['controller' => 'Auth', 'action' => 'login']);
$router->get('register', ['controller' => 'Auth', 'action' => 'register']);

// Admin routes
$router->get('admin/login', ['controller' => 'Auth', 'action' => 'login']);
$router->post('admin/login/authenticate', ['controller' => 'Auth', 'action' => 'authenticate']);
$router->get('admin/logout', ['controller' => 'Auth', 'action' => 'logout']);

$router->get('admin/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);
$router->get('admin', ['controller' => 'Dashboard', 'action' => 'index']);

$router->get('admin/products', ['controller' => 'Product', 'action' => 'index']);
$router->get('admin/products/create', ['controller' => 'Product', 'action' => 'create']);
$router->post('admin/products/store', ['controller' => 'Product', 'action' => 'store']);
$router->get('admin/products/edit', ['controller' => 'Product', 'action' => 'edit']);
$router->post('admin/products/update', ['controller' => 'Product', 'action' => 'update']);
$router->post('admin/products/delete', ['controller' => 'Product', 'action' => 'delete']);
$router->post('admin/products/status', ['controller' => 'Product', 'action' => 'status']);

$router->get('admin/categories', ['controller' => 'Category', 'action' => 'index']);
$router->get('admin/categories/create', ['controller' => 'Category', 'action' => 'create']);
$router->post('admin/categories/store', ['controller' => 'Category', 'action' => 'store']);
$router->get('admin/categories/edit', ['controller' => 'Category', 'action' => 'edit']);
$router->post('admin/categories/update', ['controller' => 'Category', 'action' => 'update']);
$router->post('admin/categories/delete', ['controller' => 'Category', 'action' => 'delete']);

$router->get('admin/orders', ['controller' => 'Order', 'action' => 'index']);
$router->get('admin/orders/view', ['controller' => 'Order', 'action' => 'show']);
$router->post('admin/orders/status', ['controller' => 'Order', 'action' => 'status']);
$router->post('admin/orders/delete', ['controller' => 'Order', 'action' => 'delete']);

$router->get('admin/users', ['controller' => 'User', 'action' => 'index']);
$router->get('admin/users/create', ['controller' => 'User', 'action' => 'create']);
$router->post('admin/users/store', ['controller' => 'User', 'action' => 'store']);
$router->get('admin/users/edit', ['controller' => 'User', 'action' => 'edit']);
$router->post('admin/users/update', ['controller' => 'User', 'action' => 'update']);
$router->post('admin/users/delete', ['controller' => 'User', 'action' => 'delete']);
$router->post('admin/users/status', ['controller' => 'User', 'action' => 'status']);

// Dispatch route
$router->dispatch($url);
