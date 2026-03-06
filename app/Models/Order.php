<?php
/**
 * Order Model
 * Handles order-related database operations
 */

require_once __DIR__ . '/Model.php';

class Order extends Model {
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'order_number', 'total_amount', 'status', 'payment_method', 'payment_status', 'shipping_name', 'shipping_phone', 'shipping_address', 'notes'];
    
    /**
     * Get orders with user info
     */
    public function getWithUser($page = 1, $perPage = 10, $search = '', $status = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.id";
        $params = [];
        
        $conditions = [];
        
        if ($search) {
            $conditions[] = "(o.order_number LIKE ? OR u.name LIKE ? OR u.phone LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($status) {
            $conditions[] = "o.status = ?";
            $params[] = $status;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY o.id DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Count orders
     */
    public function countOrders($search = '', $status = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        $conditions = [];
        
        if ($search) {
            $conditions[] = "order_number LIKE ?";
            $params[] = "%{$search}%";
        }
        
        if ($status) {
            $conditions[] = "status = ?";
            $params[] = $status;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    /**
     * Find by order number
     */
    public function findByOrderNumber($orderNumber) {
        return $this->findBy('order_number', $orderNumber);
    }
    
    /**
     * Get order items
     */
    public function getItems($orderId) {
        return $this->db->fetchAll(
            "SELECT oi.*, p.name as product_name, p.image as product_image 
             FROM order_items oi 
             LEFT JOIN products p ON oi.product_id = p.id 
             WHERE oi.order_id = ?",
            [$orderId]
        );
    }
    
    /**
     * Create order with items
     */
    public function createOrder($data, $items) {
        $this->db->beginTransaction();
        
        try {
            // Generate order number
            $data['order_number'] = 'ORD-' . time() . rand(100, 999);
            
            // Create order
            $filteredData = array_intersect_key($data, array_flip($this->fillable));
            $fields = implode(', ', array_keys($filteredData));
            $placeholders = implode(', ', array_fill(0, count($filteredData), '?'));
            
            $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
            $this->db->query($sql, array_values($filteredData));
            
            $orderId = $this->db->lastInsertId();
            
            // Insert order items
            foreach ($items as $item) {
                $sql = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity, total) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $this->db->query($sql, [
                    $orderId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['price'],
                    $item['quantity'],
                    $item['price'] * $item['quantity']
                ]);
            }
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Update order status
     */
    public function updateStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }
    
    /**
     * Get order statistics
     */
    public function getStats() {
        $stats = [];
        
        // Total orders
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table}");
        $stats['total'] = $result['total'] ?? 0;
        
        // Pending orders
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'pending'");
        $stats['pending'] = $result['total'] ?? 0;
        
        // Processing orders
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'processing'");
        $stats['processing'] = $result['total'] ?? 0;
        
        // Completed orders
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'completed'");
        $stats['completed'] = $result['total'] ?? 0;
        
        // Cancelled orders
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'cancelled'");
        $stats['cancelled'] = $result['total'] ?? 0;
        
        // Total revenue (completed orders)
        $result = $this->db->fetchOne("SELECT SUM(total_amount) as total FROM {$this->table} WHERE status = 'completed'");
        $stats['revenue'] = $result['total'] ?? 0;
        
        // Today's orders
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(created_at) = CURDATE()");
        $stats['today'] = $result['total'] ?? 0;
        
        return $stats;
    }
    
    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue($year = null) {
        $year = $year ?? date('Y');
        
        $sql = "SELECT MONTH(created_at) as month, SUM(total_amount) as revenue 
                FROM {$this->table} 
                WHERE status = 'completed' AND YEAR(created_at) = ?
                GROUP BY MONTH(created_at)
                ORDER BY month";
        
        return $this->db->fetchAll($sql, [$year]);
    }
}

