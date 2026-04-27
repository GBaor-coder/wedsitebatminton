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
$router->post('contact/send', ['controller' => 'Home', 'action' => 'contactSend']);
$router->get('contact/success', ['controller' => 'Home', 'action' => 'contactSuccess']);
$router->get('about', ['controller' => 'Home', 'action' => 'about']);
$router->get('guide', ['controller' => 'Home', 'action' => 'guide']);

// Cart & Checkout
$router->get('cart', ['controller' => 'Home', 'action' => 'cart']);
$router->get('cart/add', ['controller' => 'Home', 'action' => 'addToCart']);
$router->post('cart/update', ['controller' => 'Home', 'action' => 'updateCart']);
$router->post('cart/remove', ['controller' => 'Home', 'action' => 'removeCart']);
$router->get('checkout', ['controller' => 'Home', 'action' => 'checkout']);
$router->get('product', ['controller' => 'Home', 'action' => 'productDetail']);

// API endpoints
$router->post('api/create-order', ['controller' => 'Home', 'action' => 'createOrder']);
$router->post('api/clear-cart', ['controller' => 'Home', 'action' => 'clearCart']);

// Order Lookup (Tra cứu đơn hàng)
$router->get('tra-cuu-don-hang', ['controller' => 'Home', 'action' => 'orderLookup']);

// Profile
$router->get('profile', ['controller' => 'Home', 'action' => 'profile']);
$router->get('thanh-vien', ['controller' => 'Home', 'action' => 'profile']);
$router->get('profile/addresses', ['controller' => 'Home', 'action' => 'addresses']);
$router->post('profile/addresses', ['controller' => 'Home', 'action' => 'addresses']);
$router->post('profile/update', ['controller' => 'Home', 'action' => 'profileUpdate']);
$router->get('my-orders', ['controller' => 'Home', 'action' => 'myOrders']);
$router->get('order/{id}', ['controller' => 'Home', 'action' => 'orderDetail']);
$router->get('verify-email', ['controller' => 'Home', 'action' => 'verifyEmail']);

// Auth (frontend user)
$router->get('login', ['controller' => 'Home', 'action' => 'login']);
$router->post('login', ['controller' => 'Home', 'action' => 'loginSubmit']);
$router->get('register', ['controller' => 'Home', 'action' => 'register']);
$router->post('register', ['controller' => 'Home', 'action' => 'registerSubmit']);
$router->get('logout', ['controller' => 'Home', 'action' => 'logout']);

// Admin routes
$router->get('admin/login', ['controller' => 'Auth', 'action' => 'login']);
$router->post('admin/login/authenticate', ['controller' => 'Auth', 'action' => 'authenticate']);
$router->get('admin/logout', ['controller' => 'Auth', 'action' => 'logout']);

$router->get('admin/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);
$router->get('admin', ['controller' => 'Dashboard', 'action' => 'profile']);

$router->get('admin/products', ['controller' => 'Product', 'action' => 'index']);
$router->get('admin/products/create', ['controller' => 'Product', 'action' => 'create']);
$router->post('admin/products/store', ['controller' => 'Product', 'action' => 'store']);
$router->get('admin/products/edit', ['controller' => 'Product', 'action' => 'edit']);
$router->post('admin/products/update', ['controller' => 'Product', 'action' => 'update']);
$router->post('admin/products/delete', ['controller' => 'Product', 'action' => 'delete']);
$router->post('admin/products/status', ['controller' => 'Product', 'action' => 'status']);

$router->get('admin/posts', ['controller' => 'Post', 'action' => 'index']);
$router->get('admin/posts/create', ['controller' => 'Post', 'action' => 'create']);
$router->post('admin/posts/store', ['controller' => 'Post', 'action' => 'store']);
$router->get('admin/posts/edit', ['controller' => 'Post', 'action' => 'edit']);
$router->post('admin/posts/update', ['controller' => 'Post', 'action' => 'update']);
$router->post('admin/posts/delete', ['controller' => 'Post', 'action' => 'delete']);
$router->post('admin/posts/status', ['controller' => 'Post', 'action' => 'status']);

$router->get('admin/categories', ['controller' => 'Category', 'action' => 'index']);
$router->get('admin/categories/create', ['controller' => 'Category', 'action' => 'create']);
$router->post('admin/categories/store', ['controller' => 'Category', 'action' => 'store']);
$router->get('admin/categories/edit', ['controller' => 'Category', 'action' => 'edit']);
$router->post('admin/categories/update', ['controller' => 'Category', 'action' => 'update']);
$router->post('admin/categories/delete', ['controller' => 'Category', 'action' => 'delete']);

$router->get('admin/brands', ['controller' => 'Brand', 'action' => 'index']);
$router->get('admin/brands/create', ['controller' => 'Brand', 'action' => 'create']);
$router->post('admin/brands/store', ['controller' => 'Brand', 'action' => 'store']);
$router->get('admin/brands/edit', ['controller' => 'Brand', 'action' => 'edit']);
$router->post('admin/brands/update', ['controller' => 'Brand', 'action' => 'update']);
$router->post('admin/brands/delete', ['controller' => 'Brand', 'action' => 'delete']);
$router->get('admin/brands/filter', ['controller' => 'Brand', 'action' => 'filter']);

$router->get('admin/orders', ['controller' => 'Order', 'action' => 'index']);
$router->get('admin/orders/view', ['controller' => 'Order', 'action' => 'show']);
$router->post('admin/orders/status', ['controller' => 'Order', 'action' => 'status']);
$router->post('admin/orders/payment-status', ['controller' => 'Order', 'action' => 'paymentStatus']);
$router->post('admin/orders/delete', ['controller' => 'Order', 'action' => 'delete']);

$router->get('admin/users', ['controller' => 'User', 'action' => 'index']);
$router->get('admin/users/create', ['controller' => 'User', 'action' => 'create']);
$router->post('admin/users/store', ['controller' => 'User', 'action' => 'store']);
$router->get('admin/users/edit', ['controller' => 'User', 'action' => 'edit']);
$router->post('admin/users/update', ['controller' => 'User', 'action' => 'update']);
$router->post('admin/users/delete', ['controller' => 'User', 'action' => 'delete']);
$router->post('admin/users/status', ['controller' => 'User', 'action' => 'status']);

$router->get('admin/contacts', ['controller' => 'Contact', 'action' => 'index']);
$router->get('admin/contacts/messenger', ['controller' => 'Contact', 'action' => 'messenger']);

// Admin Guide routes
$router->get('admin/guides', ['controller' => 'Guide', 'action' => 'index']);
$router->get('admin/guides/create', ['controller' => 'Guide', 'action' => 'create']);
$router->post('admin/guides/store', ['controller' => 'Guide', 'action' => 'store']);
$router->get('admin/guides/edit', ['controller' => 'Guide', 'action' => 'edit']);
$router->post('admin/guides/update', ['controller' => 'Guide', 'action' => 'update']);
$router->post('admin/guides/delete', ['controller' => 'Guide', 'action' => 'delete']);

$router->get('admin/guide-categories', ['controller' => 'Guide', 'action' => 'categories']);
$router->get('admin/guide-categories/create', ['controller' => 'Guide', 'action' => 'createCategory']);
$router->post('admin/guide-categories/store', ['controller' => 'Guide', 'action' => 'storeCategory']);
$router->get('admin/guide-categories/edit', ['controller' => 'Guide', 'action' => 'editCategory']);
$router->post('admin/guide-categories/update', ['controller' => 'Guide', 'action' => 'updateCategory']);
$router->post('admin/guide-categories/delete', ['controller' => 'Guide', 'action' => 'deleteCategory']);

// Dispatch route
$router->dispatch($url);
