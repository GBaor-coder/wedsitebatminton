<?php
/**
 * Post Model
 * Handles post/news-related database operations
 */

require_once __DIR__ . '/Model.php';

class Post extends Model {
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'slug', 'content', 'excerpt', 'image', 'status', 'featured', 'category_id'];
    
    /**
     * Get all active posts
     */
    public function getActivePosts() {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY created_at DESC"
        );
    }
    
    /**
     * Get post by slug
     */
    public function findBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get latest posts
     */
    public function getLatest($limit = 3) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get featured posts
     */
    public function getFeatured($limit = 5) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE featured = 1 AND status = 'active' ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Get posts with pagination
     */
    public function getPaginated($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' 
                ORDER BY created_at DESC LIMIT ? OFFSET ?";
        
        return $this->db->fetchAll($sql, [$perPage, $offset]);
    }
    
    /**
     * Count posts
     */
    public function countPosts() {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'"
        );
        return $result['total'] ?? 0;
    }
    
    /**
     * Generate slug from title
     */
    public function generateSlug($title) {
        $slug = strtolower(trim($title));
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
}

