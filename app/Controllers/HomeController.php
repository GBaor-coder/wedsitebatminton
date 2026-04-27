<?php
/**
 * Home Controller
 * Handles the homepage and public pages
 */

require_once ROOT_PATH . '/app/Models/Product.php';
require_once ROOT_PATH . '/app/Models/Category.php';
require_once ROOT_PATH . '/app/Models/Brand.php';
require_once ROOT_PATH . '/app/Models/Post.php';
require_once ROOT_PATH . '/app/Models/User.php';
require_once ROOT_PATH . '/app/Models/Guide.php';

class HomeController {
    private $productModel;
    private $categoryModel;
    private $brandModel;
    private $postModel;
    private $userModel;
    private $params = [];
    private $menu = [];
    private $guideModel;
    private $guideMenu = [];
    private $rememberSecret = 'jpsport_remember_secret_2026';

    public function __construct($params = []) {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->brandModel = new Brand();
        $this->postModel = new Post();
        $this->userModel = new User();
        $this->guideModel = new Guide();
        $this->params = $params;
        $this->autoLoginFromCookie();
        $this->menu = $this->categoryModel->getCategoriesWithBrands();
        $this->guideMenu = $this->guideModel->getCategoriesWithItems();
    }

    /**
     * Get route parameter
     */
    private function getParam($key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    private function autoLoginFromCookie() {
        $session = new \Session();
        if ($session->isLoggedIn()) {
            return;
        }

        if (empty($_COOKIE['remember_me'])) {
            return;
        }

        $payload = @json_decode(base64_decode($_COOKIE['remember_me']), true);
        if (!$payload || empty($payload['id']) || empty($payload['expires']) || empty($payload['sig'])) {
            return;
        }

        if ($payload['expires'] < time()) {
            setcookie('remember_me', '', time() - 3600, '/');
            return;
        }

        $user = $this->userModel->find($payload['id']);

        if (!$user) {
            return;
        }

        $expectedSig = hash_hmac('sha256', $user['id'] . '|' . $user['email'] . '|' . $payload['expires'], $this->rememberSecret);
        if (!hash_equals($expectedSig, $payload['sig'])) {
            return;
        }

        // auto login
        $session->set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'] ?? '',
            'address' => $user['address'] ?? '',
            'country' => $user['country'] ?? 'Việt Nam',
            'city' => $user['city'] ?? '',
            'birthdate' => $user['birthdate'] ?? '',
            'role' => $user['role'] ?? 'customer',
        ]);
    }
    
    /**
     * View - Render a view file
     */
    private function view($view, $data = []) {
        // Always pass menu data to the view so header can render dynamic categories and brands
        $data['menu'] = $this->menu;
        $data['guideMenu'] = $this->guideMenu;
        extract($data);
        
        $viewFile = ROOT_PATH . '/resources/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View not found: " . $view;
        }
    }
    
    /**
     * Redirect - Helper method for redirects
     */
    private function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Validate CSRF (copied from Controller for compatibility)
     */
    private function validateCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (!\CSRF::validate($token)) {
            return false;
        }
        return true;
    }
    
    // ... [rest of existing methods unchanged - profile, myOrders, etc. remain the same]

    /**
     * Profile Addresses - Shipping addresses page
     */
    public function addresses() {
        $session = new \Session();
        if (!$session->isLoggedIn()) {
            $this->redirect('/websitebatminton/login');
            return;
        }

        $user = $session->user();
        if (($user['role'] ?? 'customer') === 'admin') {
            $this->redirect('/websitebatminton/admin/dashboard');
            return;
        }

        $userId = $user['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update address
            $address = trim($_POST['address'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $country = trim($_POST['country'] ?? 'Việt Nam');
            $phone = trim($_POST['phone'] ?? $user['phone']);

            $db = \Database::getInstance();
            $query = "UPDATE users SET address = ?, city = ?, country = ?, phone = ? WHERE id = ?";
            $result = $db->query($query, [$address, $city, $country, $phone, $userId]);

            if ($result) {
                // Update session
                $user['address'] = $address;
                $user['city'] = $city;
                $user['country'] = $country;
                $user['phone'] = $phone;
                $session->set('user', $user);
                $_SESSION['success'] = 'Cập nhật địa chỉ giao hàng thành công';
            } else {
                $_SESSION['error'] = 'Lỗi cập nhật địa chỉ';
            }
            $this->redirect('/websitebatminton/profile/addresses');
            return;
        }

        $data = [
            'user' => $user
        ];
        $this->view('profile-addresses', $data);
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

    // [Include ALL other existing methods exactly as they are from the current file - products, productDetail, category, news, newsDetail, cart, profile, profileUpdate, myOrders, login, loginSubmit, register, registerSubmit, logout, verifyEmail, contact, contactSend, contactSuccess, about, adminContacts, checkout, createOrder, clearCart, orderDetail, guide]
    
    /**
     * Products - Product listing page // trang danh sách sản phẩm với phân trang, tìm kiếm và lọc theo danh mục
     */
    public function products() {
        $categoryId = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 12;
        
        // New filters
        $brand = $_GET['brand'] ?? null; // category id
        $priceRange = $_GET['price'] ?? null; // e.g., "0-500000"
        $color = $_GET['color'] ?? null;
        $size = $_GET['size'] ?? null;
        $sort = $_GET['sort'] ?? 'newest';
        
        // Get products with pagination // lấy sản phẩm có phân trang, tìm kiếm và lọc theo danh mục
        $products = $this->productModel->getPaginatedWithFilters($page, $perPage, $search, $categoryId, $brand, $priceRange, $color, $size, $sort);
        $totalProducts = $this->productModel->countProductsWithFilters($search, $categoryId, $brand, $priceRange, $color, $size);
        $totalPages = ceil($totalProducts / $perPage);
        
        // Get categories and brands // để hiển thị bộ lọc danh mục và hãng trên trang sản phẩm
        $categories = $this->categoryModel->getActiveCategories();
        $brands = $this->brandModel->getActiveBrands();
        
        $data = [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'categoryId' => $categoryId,
            'brand' => $brand,
            'priceRange' => $priceRange,
            'color' => $color,
            'size' => $size,
            'sort' => $sort
        ];
        
        $this->view('products', $data);
    }
    
    /**
     * Product Detail - Single product page // chi tiết sản phẩm
     */
    public function productDetail() {
        // Làm việc với params router và fallback query string id (nếu slug bị thiếu)
        $slug = $this->getParam('slug', '') ?: ($_GET['slug'] ?? '');
        $product = null;

        if ($slug) {
            $product = $this->productModel->findBySlug($slug);
        }

        if (!$product && !empty($_GET['id'])) {
            $product = $this->productModel->find(intval($_GET['id']));
        }

        if (!$product) {
            echo "Product not found";
            return;
        }
        
        // Lưu trữ lịch sử sản phẩm đã xem vào session
        $_SESSION['viewed_items'] = $_SESSION['viewed_items'] ?? [];
        $existingIndex = null;
        foreach ($_SESSION['viewed_items'] as $idx => $viewedItem) {
            if (isset($viewedItem['id']) && $viewedItem['id'] == $product['id']) {
                $existingIndex = $idx;
                break;
            }
        }
        if ($existingIndex !== null) {
            // kéo lên đầu nếu đã tồn tại
            $saved = $_SESSION['viewed_items'][$existingIndex];
            unset($_SESSION['viewed_items'][$existingIndex]);
            array_unshift($_SESSION['viewed_items'], $saved);
        } else {
            array_unshift($_SESSION['viewed_items'], [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'image' => $product['image'] ?? '',
                'price' => $product['price']
            ]);
            // giữ tối đa 10 mục
            if (count($_SESSION['viewed_items']) > 10) {
                array_pop($_SESSION['viewed_items']);
            }
        }

        // Get related products // sản phẩm liên quan cùng danh mục
        $relatedProducts = $this->productModel->getByCategory($product['category_id'], 4);
        
        $data = [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];
        
        $this->view('product-detail', $data);
    }
    
    /**
     * Category - Products by category // trang sản phẩm theo danh mục
     * 
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
     * News - Blog/News listing // trang tin tức, bài viết
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
     * News Detail - Single post page // chi tiết tin tức, bài viết
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
     * Cart - Cart page // thêm sản phẩm vào giỏ hàng và hiển thị giỏ hàng
     */
    public function addToCart() {
        $productId = $_GET['product_id'] ?? null;
        if (!$productId) {
            $this->redirect('/websitebatminton/products');
            return;
        }

        $product = $this->productModel->find($productId);
        if (!$product) {
            $this->redirect('/websitebatminton/products');
            return;
        }

        // Add to cart helper // thêm sản phẩm vào giỏ hàng trong session
        addToCart($productId, 1, [
            'name' => $product['name'],
            'slug' => $product['slug'],
'price' => $product['price'],
            'image' => $product['image'] ?? ''
        ]);

        $this->redirect('/websitebatminton/cart');
    }

    public function cart() {
        // Get cart from session
        $cartItems = $_SESSION['cart'] ?? [];
        $viewedItems = $_SESSION['viewed_items'] ?? [];
        $cartTotal = 0;
        
        foreach ($cartItems as $item) {
            $price = $item['price'];
            $cartTotal += $price * $item['quantity'];
        }
        
        $data = [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'viewedItems' => $viewedItems
        ];
        
        $this->view('cart', $data);
    }

    public function updateCart() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $id = $_POST['id'] ?? null;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;

        if (!$id || $quantity === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        if ($quantity <= 0) {
            removeFromCart($id);
        } else {
            updateCartItem($id, $quantity);
        }

        $cartItems = getCart();
        $cartTotalValue = cartTotal();

        $item = $cartItems[$id] ?? null;
        $subtotal = 0;
        if ($item) {
            $price = $item['price'];
            $subtotal = $price * $item['quantity'];
        }

        echo json_encode([
            'success' => true,
            'cartTotal' => formatPrice($cartTotalValue),
            'subtotal' => $item ? formatPrice($subtotal) : formatPrice(0),
            'itemCount' => count($cartItems)
        ]);
    }

    public function removeCart() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
            return;
        }

        removeFromCart($id);

        $cartTotalValue = cartTotal();

        echo json_encode([
            'success' => true,
            'cartTotal' => formatPrice($cartTotalValue),
            'itemCount' => count(getCart())
        ]);
    }

    public function profile() {
        $session = new \Session();
        if (!$session->isLoggedIn()) {
            $this->redirect('/websitebatminton/login');
            return;
        }

        $user = $session->user();
        if (($user['role'] ?? 'customer') === 'admin') {
            // Nếu admin vô nhầm trang thành viên thì chuyển về admin dashboard
            $this->redirect('/websitebatminton/admin/dashboard');
            return;
        }

        $profile = [
            'id' => $user['id'],
            'name' => $user['name'] ?? 'Người dùng',
            'email' => $user['email'] ?? '',
            'phone' => $user['phone'] ?? '',
            'gender' => $user['gender'] ?? 'male',
            'address' => $user['address'] ?? '',
            'country' => $user['country'] ?? 'Việt Nam',
            'city' => $user['city'] ?? '',
            'birthdate' => $user['birthdate'] ?? ''
        ];

        $data = ['profile' => $profile];
        $this->view('profile', $data);
    }

    /**
     * Profile Update - Update user profile information
     */
    public function profileUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/websitebatminton/thanh-vien');
            return;
        }

        $session = new \Session();
        if (!$session->isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            $this->redirect('/websitebatminton/login');
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Lỗi bảo mật CSRF. Vui lòng thử lại.';
            $this->redirect('/websitebatminton/thanh-vien');
            return;
        }

        $user = $session->user();
        $userId = $user['id'];

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'gender' => trim($_POST['gender'] ?? 'male'),
            'address' => trim($_POST['address'] ?? ''),
            'country' => trim($_POST['country'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'birthdate' => !empty(trim($_POST['birthdate'] ?? '')) ? trim($_POST['birthdate']) : NULL
        ];

        // Validate required fields
        if (empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin (Tên và Email bắt buộc)';
            $this->redirect('/websitebatminton/profile');
            return;
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            $this->redirect('/websitebatminton/profile');
            return;
        }

        try {
            $db = \Database::getInstance();
            $query = "UPDATE users SET name = ?, email = ?, phone = ?, gender = ?, address = ?, country = ?, city = ?, birthdate = ?, updated_at = ? WHERE id = ?";
            $params = [
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['gender'],
                $data['address'],
                $data['country'],
                $data['city'],
                $data['birthdate'],
                date('Y-m-d H:i:s'),
                $userId
            ];
            
            if (!$db->query($query, $params)) {
                throw new \Exception('Không thể cập nhật dữ liệu');
            }
            
            // Update session user data
            $user['name'] = $data['name'];
            $user['email'] = $data['email'];
            $user['phone'] = $data['phone'];
            $user['gender'] = $data['gender'];
            $user['address'] = $data['address'];
            $user['country'] = $data['country'];
            $user['city'] = $data['city'];
            $user['birthdate'] = $data['birthdate'];
            $session->set('user', $user);

            $_SESSION['success'] = 'Cập nhật thông tin thành công';
            $this->redirect('/websitebatminton/thanh-vien');
        } catch (Exception $e) {
            error_log('Profile update error: ' . $e->getMessage());
            $_SESSION['error'] = 'Lỗi cập nhật thông tin: ' . $e->getMessage();
            $this->redirect('/websitebatminton/thanh-vien');
        }
    }

    public function myOrders() {
        $session = new \Session();
        if (!$session->isLoggedIn()) {
            $this->redirect('/websitebatminton/login');
            return;
        }

        $user = $session->user();
        $db = \Database::getInstance();
        
        // Get user's orders
        $orders = $db->fetchAll(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC",
            [$user['id']]
        );

        $data = [
            'user' => $user,
            'orders' => $orders ?? []
        ];
        
        $this->view('my-orders', $data);
    }

    public function login() {
        $session = new \Session();
        if ($session->isLoggedIn()) {
            if (($session->user()['role'] ?? 'customer') === 'admin') {
                $this->redirect('/websitebatminton/admin/dashboard');
            } else {
                $this->redirect('/websitebatminton/thanh-vien');
            }
            return;
        }
        $this->view('login');
    }

    public function loginSubmit() {
        $session = new \Session();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/websitebatminton/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email và mật khẩu không được để trống';
            $this->redirect('/websitebatminton/login');
            return;
        }

        $user = $this->userModel->verifyPassword($email, $password);
        if (!$user) {
            $_SESSION['error'] = 'Đăng nhập không thành công. Bạn chưa đăng kí tài khoản';
            $this->redirect('/websitebatminton/login');
            return;
        }

        if (($user['role'] ?? 'customer') !== 'admin' && empty($user['email_verified_at'])) {
            $_SESSION['error'] = 'Vui lòng xác thực email trước khi đăng nhập.';
            $this->redirect('/websitebatminton/login');
            return;
        }

        $session->set('user', [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'] ?? '',
            'address' => $user['address'] ?? '',
            'country' => $user['country'] ?? 'Việt Nam',
            'city' => $user['city'] ?? '',
            'birthdate' => $user['birthdate'] ?? '',
            'role' => $user['role'] ?? 'customer',
        ]);

        if (!empty($_POST['remember'])) {
            $expires = time() + 30 * 24 * 60 * 60;
            $tokenData = [
                'id' => $user['id'],
                'expires' => $expires,
                'sig' => hash_hmac('sha256', $user['id'] . '|' . $user['email'] . '|' . $expires, $this->rememberSecret)
            ];
            setcookie('remember_me', base64_encode(json_encode($tokenData)), $expires, '/');
        }

        if (($user['role'] ?? 'customer') === 'admin') {
            $this->redirect('/websitebatminton/admin/dashboard');
        } else {
            $this->redirect('/websitebatminton/thanh-vien');
        }
    }

    // [Continue with ALL other methods exactly as they exist... to avoid incomplete file]
    
    public function register() {
        $session = new \Session();
        if ($session->isLoggedIn()) {
            $this->redirect('/websitebatminton/profile');
            return;
        }
        $this->view('register');
    }

    public function registerSubmit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/websitebatminton/register');
            return;
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $name = trim($firstName . ' ' . $lastName);
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            $this->redirect('/websitebatminton/register');
            return;
        }

        if ($password !== $confirm) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
            $this->redirect('/websitebatminton/register');
            return;
        }

        $userId = $this->userModel->createUser([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => null
        ]);

        $verificationToken = bin2hex(random_bytes(16));
        $_SESSION['pending_email_verification'] = $_SESSION['pending_email_verification'] ?? [];
        $_SESSION['pending_email_verification'][$verificationToken] = $userId;

        $_SESSION['success'] = 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực (có thể dùng link sau khi demo).';
        $_SESSION['verification_link'] = '/websitebatminton/verify-email?token=' . $verificationToken;
        $this->redirect('/websitebatminton/login');
    }

    // [Remaining methods: logout, verifyEmail, contact, contactSend, contactSuccess, about, adminContacts, checkout, createOrder, clearCart, orderDetail, guide - copy exactly as they exist]

    public function logout() {
        $session = new \Session();
        $session->destroy();
        setcookie('remember_me', '', time() - 3600, '/');
        $this->redirect('/websitebatminton/login');
    }

    public function verifyEmail() {
        $token = $_GET['token'] ?? '';
        if (empty($token) || empty($_SESSION['pending_email_verification'][$token])) {
            $_SESSION['error'] = 'Liên kết xác thực không hợp lệ hoặc đã hết hạn.';
            $this->redirect('/websitebatminton/login');
            return;
        }

        $userId = $_SESSION['pending_email_verification'][$token];
        $this->userModel->updateUser($userId, ['email_verified_at' => date('Y-m-d H:i:s')]);

        unset($_SESSION['pending_email_verification'][$token]);

        $_SESSION['success'] = 'Xác thực email thành công. Bạn có thể đăng nhập ngay.';
        $this->redirect('/websitebatminton/login');
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
        if (empty($_POST['name']) || empty($_POST['phone'] ) || empty($_POST['message'])) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: /websitebatminton/contact');
            exit;
        }
        
        // Save to DB
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
        
        $this->view('admin/contacts/index', $data);
    }
    
    /**
     * Checkout - Checkout page
     */
    public function checkout() {
        $session = new \Session();
        if (!$session->isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục thanh toán';
            $this->redirect('/websitebatminton/login');
            return;
        }

        // Get cart from session
        $cartItems = $_SESSION['cart'] ?? [];
        
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng của bạn trống';
            $this->redirect('/websitebatminton/cart');
            return;
        }

        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['price'];
            $cartTotal += $price * $item['quantity'];
        }

        $user = $session->user();
        $data = [
            'user' => $user,
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ];

        $this->view('checkout', $data);
    }

    /**
     * Create Order - API endpoint to create order from checkout
     */
    public function createOrder() {
        header('Content-Type: application/json');
        $session = new \Session();

        if (!$session->isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }

        // Get POST data
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!$data['name'] || !$data['email'] || 
            !$data['phone'] || !$data['address'] || !$data['city']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không đầy đủ']);
            return;
        }

        // Get cart from session
        $cartItems = $_SESSION['cart'] ?? [];
        if (empty($cartItems)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
            return;
        }

        // Calculate total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
        }

        // Shipping fees
        $shippingFees = [
            'standard' => 15000,
            'express' => 30000,
            'overnight' => 50000
        ];
        $shipping = $data['shipping'] ?? 'standard';
        $shippingFee = $shippingFees[$shipping] ?? 15000;
        $totalAmount = $subtotal + $shippingFee;

        // Create order
        try {
            $db = \Database::getInstance();
            $userId = $session->user()['id'];

            // Generate unique order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . uniqid();

            // Insert order
            $query = "INSERT INTO orders (user_id, order_number, customer_name, customer_email, 
                      customer_phone, customer_address, total_amount, shipping_fee, discount_amount, 
                      payment_method, payment_status, status, shipping_method, shipping_address, 
                      shipping_city, shipping_country, subtotal, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $params = [
                $userId,
                $orderNumber,
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['address'], // customer_address
                $totalAmount,
                $shippingFee,
                0, // discount_amount
                $data['payment'] ?? 'cod',
                'pending', // payment_status
                'pending', // status
                $shipping,
                $data['address'], // shipping_address
                $data['city'],
                $data['country'] ?? 'Vietnam',
                $subtotal,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ];

            $stmt = $db->query($query, $params);
            $orderId = $db->getConnection()->lastInsertId();

            if ($orderId) {
                // Store order items
                foreach ($cartItems as $item) {
                    $itemQuery = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) 
                                 VALUES (?, ?, ?, ?, ?, ?)";
                    $price = $item['sale_price'] ?? $item['price'];
                    $itemSubtotal = $price * $item['quantity'];
                    
                    $itemParams = [
                        $orderId,
                        $item['id'],
                        $item['name'],
                        $price,
                        $item['quantity'],
                        $itemSubtotal
                    ];
                    $db->query($itemQuery, $itemParams);
                }

                // Clear cart from session
                unset($_SESSION['cart']);

                echo json_encode([
                    'success' => true,
                    'message' => 'Đơn hàng đã được tạo',
                    'order_id' => $orderId,
                    'order_number' => $orderNumber
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Lỗi khi tạo đơn hàng']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Clear Cart - API endpoint to clear shopping cart
     */
    public function clearCart() {
        header('Content-Type: application/json');
        unset($_SESSION['cart']);
        echo json_encode(['success' => true, 'message' => 'Giỏ hàng đã được xóa']);
    }

    /**
     * Order Detail - View single order details
     */
    public function orderDetail() {
        $session = new \Session();
        if (!$session->isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            $this->redirect('/websitebatminton/login');
            return;
        }

        // Get order ID from route parameter
        $orderId = $this->getParam('id');
        if (!$orderId) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            $this->redirect('/websitebatminton/my-orders');
            return;
        }

        $db = \Database::getInstance();
        $userId = $session->user()['id'];

        // Fetch order - ensure it belongs to current user
        $query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
        $order = $db->fetchOne($query, [$orderId, $userId]);

        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            $this->redirect('/websitebatminton/my-orders');
            return;
        }

        // Fetch order items
        $itemsQuery = "SELECT oi.id, oi.order_id, oi.product_id, oi.product_name, oi.product_price as price, oi.quantity, oi.subtotal, oi.created_at, p.name as product_name, p.image as product_image 
                      FROM order_items oi 
                      LEFT JOIN products p ON oi.product_id = p.id 
                      WHERE oi.order_id = ?";
        $items = $db->fetchAll($itemsQuery, [$orderId]);

        $data = [
            'order' => $order,
            'items' => $items
        ];

        $this->view('order-detail', $data);
    }
    
    /**
     * Guide - Guide/How-to page
     */
    public function guide() {
        $itemId = $_GET['item'] ?? null;
        $categoryId = $_GET['category'] ?? null;
        
        if ($itemId) {
            // Show specific guide content detail
            $guide = $this->guideModel->getById($itemId);
            $data = ['guide' => $guide];
        } elseif ($categoryId) {
            // Direct to first content of the category (no sub-menu display)
            $guide = $this->guideModel->getFirstContentByCategory($categoryId);
            $data = ['guide' => $guide];
        } else {
            // Show all categories (no items, just category names)
            $categories = $this->guideModel->getCategories();
            $data = ['categories' => $categories];
        }
        
        $this->view('guide', $data);
    }

    /**
     * Order Lookup - Tra cứu đơn hàng (không cần đăng nhập)
     */
    public function orderLookup() {
        $orderNumber = trim($_GET['order_number'] ?? '');
        $phone = trim($_GET['phone'] ?? '');
        
        $order = null;
        $items = [];
        
        if ($orderNumber || $phone) {
            $db = \Database::getInstance();
            
            if ($orderNumber) {
                // Tìm theo mã đơn hàng
                $order = $db->fetchOne(
                    "SELECT * FROM orders WHERE order_number = ?",
                    [$orderNumber]
                );
            } elseif ($phone) {
                // Tìm theo số điện thoại (lấy đơn mới nhất)
                $order = $db->fetchOne(
                    "SELECT * FROM orders WHERE customer_phone = ? ORDER BY created_at DESC LIMIT 1",
                    [$phone]
                );
            }
            
            // Lấy chi tiết sản phẩm trong đơn hàng
            if ($order) {
                $items = $db->fetchAll(
                    "SELECT oi.id, oi.order_id, oi.product_id, oi.product_name, oi.product_price as price, oi.quantity, oi.subtotal, oi.created_at, p.name as product_name, p.image as product_image 
                     FROM order_items oi 
                     LEFT JOIN products p ON oi.product_id = p.id 
                     WHERE oi.order_id = ?",
                    [$order['id']]
                );
            }
        }
        
        $data = [
            'orderNumber' => $orderNumber,
            'phone' => $phone,
            'order' => $order,
            'items' => $items
        ];
        
        $this->view('order-lookup', $data);
    }

}



