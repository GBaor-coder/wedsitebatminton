<?php
/**
 * Order Controller
 * Handles order management
 */

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../Models/Order.php';

class OrderController extends AdminBaseController {
    private $orderModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        
        $this->orderModel = new Order();
    }
    
    /**
     * List all orders
     */
    public function index() {
        $search = $this->getSearch();
        $page = $this->getPage();
        $perPage = 10;
        
        $status = $_GET['status'] ?? null;
        
        // Get orders
        $orders = $this->orderModel->getWithUser($page, $perPage, $search, $status);
        $total = $this->orderModel->countOrders($search, $status);
        
        // Pagination
        $pagination = $this->paginate($total, $perPage);
        
        $data = [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'search' => $search,
            'selected_status' => $status,
            'pagination' => $pagination
        ];
        
        $this->render('orders/index', $data);
    }
    
    /**
     * View order details
     */
    public function show() {
        $id = $_GET['id'] ?? 0;
        
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            $this->session->flash('error', 'Đơn hàng không tồn tại');
            $this->redirect('/websitebatminton/admin/orders');
        }
        
        // Get order items
        $items = $this->orderModel->getItems($id);
        
        $data = [
            'title' => 'Chi tiết đơn hàng #' . $order['order_number'],
            'order' => $order,
            'items' => $items
        ];
        
        $this->render('orders/view', $data);
    }
    
    /**
     * Update order status
     */
    public function status() {
        if (!$this->isPost()) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request']);
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->jsonResponse(['success' => false, 'message' => 'Token không hợp lệ']);
        }
        
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        // Valid statuses
        $validStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            $this->jsonResponse(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
        }
        
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            $this->jsonResponse(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        }
        
        try {
            $this->orderModel->updateStatus($id, $status);
            $this->jsonResponse(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái']);
        }
    }
    
    /**
     * Delete order
     */
    public function delete() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/orders');
        }
        
        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/orders');
        }
        
        $id = $_POST['id'] ?? 0;
        
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            $this->session->flash('error', 'Đơn hàng không tồn tại');
            $this->redirect('/websitebatminton/admin/orders');
        }
        
        try {
            $this->orderModel->delete($id);
            $this->session->flash('success', 'Xóa đơn hàng thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi khi xóa đơn hàng');
        }
        
        $this->redirect('/websitebatminton/admin/orders');
    }
}

