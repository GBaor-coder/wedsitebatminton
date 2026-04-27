<?php
/**
 * Product Model
 * Handles product-related database operations
 */

require_once __DIR__ . '/Model.php';

class Product extends Model {
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'slug', 'category_id', 'brand_id', 'description', 'content', 'price', 'quantity', 'sku', 'image', 'images', 'featured', 'status', 'views'];
    
    /**
     * Get all active products
     */
    public function getActiveProducts() {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY id DESC"
        );
    }
    
    /**
     * Get product by slug
     */
    public function findBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get products by category
     */
    public function getByCategory($categoryId, $limit = 10) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE category_id = ? AND status = 'active' ORDER BY id DESC LIMIT ?",
            [$categoryId, (int)$limit]
        );
    }
    
    /**
     * Get featured products
     */
    public function getFeatured($limit = 10) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE featured = 1 AND status = 'active' ORDER BY id DESC LIMIT ?",
            [(int)$limit]
        );
    }
    
    /**
     * Get products with pagination and search
     */
    public function getPaginated($page = 1, $perPage = 10, $search = '', $categoryId = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id";
        $params = [];
        
        $conditions = ["p.status = 'active'"];
        
        if ($search) {
            $conditions[] = "(p.name LIKE ? OR p.description LIKE ? OR p.sku LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($categoryId) {
            $conditions[] = "p.category_id = ?";
            $params[] = $categoryId;
        }
        
        $sql .= " WHERE " . implode(' AND ', $conditions);
        $sql .= " ORDER BY p.id DESC LIMIT ? OFFSET ?";
        $params[] = (int)$perPage;
        $params[] = (int)$offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get products with pagination and advanced filters
     */
    public function getPaginatedWithFilters($page = 1, $perPage = 10, $search = '', $categoryId = null, $brand = null, $priceRange = null, $color = null, $size = null, $sort = 'newest') {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id";
        $params = [];
        
        $conditions = ["p.status = 'active'"];
        
        if ($search) {
            $conditions[] = "(p.name LIKE ? OR p.description LIKE ? OR p.sku LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($categoryId) {
            $conditions[] = "p.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($brand) {
            $conditions[] = "p.brand_id = ?";
            $params[] = $brand;
        }
        
        if ($priceRange) {
            list($min, $max) = explode('-', $priceRange);
            if ($min !== '' && $max !== '') {
                $conditions[] = "p.price BETWEEN ? AND ?";
                $params[] = (float)$min;
                $params[] = (float)$max;
            } elseif ($min !== '') {
                $conditions[] = "p.price >= ?";
                $params[] = (float)$min;
            } elseif ($max !== '') {
                $conditions[] = "p.price <= ?";
                $params[] = (float)$max;
            }
        }
        
        // Note: Color and size filters are placeholders since these fields don't exist in DB
        // You may need to add these fields to the products table
        if ($color) {
            // Placeholder: assume color is stored in description or a new field
            $conditions[] = "p.description LIKE ?";
            $params[] = "%{$color}%";
        }
        
        if ($size) {
            // Placeholder: assume size is stored in description or a new field
            $conditions[] = "p.description LIKE ?";
            $params[] = "%{$size}%";
        }
        
        $sql .= " WHERE " . implode(' AND ', $conditions);
        
        // Sorting
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY p.name ASC";
                break;
            default:
                $sql .= " ORDER BY p.id DESC";
        }
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = (int)$perPage;
        $params[] = (int)$offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Count products
     */
    public function countProducts($search = '', $categoryId = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        $conditions = ["status = 'active'"];
        
        if ($search) {
            $conditions[] = "(name LIKE ? OR description LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($categoryId) {
            $conditions[] = "category_id = ?";
            $params[] = $categoryId;
        }
        
        $sql .= " WHERE " . implode(' AND ', $conditions);
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    /**
     * Count products with advanced filters
     */
    public function countProductsWithFilters($search = '', $categoryId = null, $brand = null, $priceRange = null, $color = null, $size = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        $conditions = ["status = 'active'"];
        
        if ($search) {
            $conditions[] = "(name LIKE ? OR description LIKE ? OR sku LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($categoryId) {
            $conditions[] = "category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($brand) {
            $conditions[] = "brand_id = ?";
            $params[] = $brand;
        }
        
        if ($priceRange) {
            list($min, $max) = explode('-', $priceRange);
            if ($min !== '' && $max !== '') {
                $conditions[] = "price BETWEEN ? AND ?";
                $params[] = (float)$min;
                $params[] = (float)$max;
            } elseif ($min !== '') {
                $conditions[] = "price >= ?";
                $params[] = (float)$min;
            } elseif ($max !== '') {
                $conditions[] = "price <= ?";
                $params[] = (float)$max;
            }
        }
        
        if ($color) {
            $conditions[] = "description LIKE ?";
            $params[] = "%{$color}%";
        }
        
        if ($size) {
            $conditions[] = "description LIKE ?";
            $params[] = "%{$size}%";
        }
        
        $sql .= " WHERE " . implode(' AND ', $conditions);
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    /**
     * Generate slug from name
     */
    public function generateSlug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists
        $existing = $this->findBySlug($slug);
        if ($existing) {
            $slug = $slug . '-' . time();
        }
        
        return $slug;
    }
    
    /**
     * Update product status
     */
    public function updateStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }
    
    /**
     * Update product quantity
     */
    public function updateQuantity($id, $quantity) {
        return $this->db->query(
            "UPDATE {$this->table} SET quantity = quantity - ? WHERE {$this->primaryKey} = ?",
            [$quantity, $id]
        );
    }
    
    /**
     * Get low stock products
     */
    public function getLowStock($threshold = 10) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE quantity < ? AND status = 'active' ORDER BY quantity ASC",
            [$threshold]
        );
    }
    
    /**
     * Get product statistics
     */
    public function getStats() {
        $stats = [];
        
        // Total products
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table}");
        $stats['total'] = $result['total'] ?? 0;
        
        // Active products
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'");
        $stats['active'] = $result['total'] ?? 0;
        
        // Out of stock
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE quantity = 0");
        $stats['out_of_stock'] = $result['total'] ?? 0;
        
        // Low stock
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table} WHERE quantity > 0 AND quantity < 10");
        $stats['low_stock'] = $result['total'] ?? 0;
        
        return $stats;
    }
}
