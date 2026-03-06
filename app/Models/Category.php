<?php
/**
 * Category Model
 * Handles category-related database operations
 */

require_once __DIR__ . '/Model.php';

class Category extends Model {
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'slug', 'description', 'status', 'parent_id'];
    
    /**
     * Get all active categories
     */
    public function getActiveCategories() {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name ASC"
        );
    }
    
    /**
     * Get category by slug
     */
    public function findBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get categories with pagination
     */
    public function getPaginated($page = 1, $perPage = 10, $search = '') {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE name LIKE ? OR description LIKE ?";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm];
        }
        
        $sql .= " ORDER BY {$this->primaryKey} DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Count categories
     */
    public function countCategories($search = '') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE name LIKE ? OR description LIKE ?";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm];
        }
        
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
     * Update category status
     */
    public function updateStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }
}

