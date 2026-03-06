<?php
/**
 * Base Model Class
 * Parent class for all models
 */

require_once __DIR__ . '/../Core/Database.php';

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all records
     */
    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table}");
    }

    /**
     * Get records with pagination
     */
    public function paginate($page = 1, $perPage = 10, $search = '', $where = '') {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if ($where) {
            $sql .= " WHERE " . $where;
        }

        $sql .= " ORDER BY {$this->primaryKey} DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Count total records
     */
    public function count($where = '') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if ($where) {
            $sql .= " WHERE " . $where;
        }

        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Find by primary key
     */
    public function find($id) {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Find single record by field
     */
    public function findBy($field, $value) {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE {$field} = ?",
            [$value]
        );
    }

    /**
     * Find multiple records by field
     */
    public function findAllBy($field, $value) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$field} = ?",
            [$value]
        );
    }

    /**
     * Create new record
     */
    public function create($data) {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));

        $fields = implode(', ', array_keys($filteredData));
        $placeholders = implode(', ', array_fill(0, count($filteredData), '?'));

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";

        $this->db->query($sql, array_values($filteredData));

        return $this->db->lastInsertId();
    }

    /**
     * Update record
     */
    public function update($id, $data) {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));

        $sets = implode(' = ?, ', array_keys($filteredData)) . ' = ?';

        $sql = "UPDATE {$this->table} SET {$sets} WHERE {$this->primaryKey} = ?";

        $params = array_values($filteredData);
        $params[] = $id;

        return $this->db->query($sql, $params);
    }

    /**
     * Delete record
     */
    public function delete($id) {
        return $this->db->query(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Search records
     */
    public function search($columns, $keyword) {
        $conditions = [];
        $params = [];

        foreach ($columns as $column) {
            $conditions[] = "{$column} LIKE ?";
            $params[] = "%{$keyword}%";
        }

        $where = implode(' OR ', $conditions);

        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$where}",
            $params
        );
    }
}
