<?php
/**
 * Post Controller - Admin CRUD for News/Blog Posts
 */

require_once __DIR__ . '/AdminBaseController.php';
require_once __DIR__ . '/../Models/Post.php';

class PostController extends AdminBaseController {
    private $postModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->postModel = new Post();
    }
    
    /**
     * List all posts with pagination and search
     */
    public function index() {
        $search = $this->getSearch();
        $page = $this->getPage();
        $perPage = 10;
        
        // Get posts
        $posts = $this->postModel->getPaginated($page, $perPage, $search);
        $total = $this->postModel->countPosts($search);
        
        // Pagination
        $pagination = $this->paginate($total, $perPage);
        
        $data = [
            'title' => 'Quản lý bài viết',
            'posts' => $posts,
            'search' => $search,
            'pagination' => $pagination
        ];
        
        $this->render('posts/index', $data);
    }
    
    /**
     * Show create post form
     */
    public function create() {
        $data = [
            'title' => 'Thêm bài viết mới',
            'post' => null
        ];
        $this->render('posts/form', $data);
    }
    
    /**
     * Store new post
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/posts');
        }
        
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/posts/create');
        }
        
        $errors = $this->validatePost($_POST);
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/posts/create');
        }
        
        $image = $this->uploadImage($_FILES['image'] ?? []);
        
        $data = [
            'title' => trim($_POST['title']),
            'slug' => $this->postModel->generateSlug($_POST['title']),
            'excerpt' => trim($_POST['excerpt']),
            'content' => $_POST['content'],
            'category' => $_POST['category'] ?? 'news',
            'image' => $image,
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured']) ? 1 : 0
        ];
        
        try {
            $this->postModel->create($data);
            $this->session->flash('success', 'Thêm bài viết thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi thêm bài viết: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/posts');
    }
    
    /**
     * Show edit post form
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->session->flash('error', 'Bài viết không tồn tại');
            $this->redirect('/websitebatminton/admin/posts');
        }
        
        $data = [
            'title' => 'Chỉnh sửa bài viết',
            'post' => $post
        ];
        $this->render('posts/form', $data);
    }
    
    /**
     * Update post
     */
    public function update() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/posts');
        }
        
        $id = $_POST['id'] ?? 0;
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->session->flash('error', 'Bài viết không tồn tại');
            $this->redirect('/websitebatminton/admin/posts');
        }
        
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/posts/edit?id=' . $id);
        }
        
        $errors = $this->validatePost($_POST);
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->session->flash('error', $error);
            }
            $this->redirect('/websitebatminton/admin/posts/edit?id=' . $id);
        }
        
        $image = $post['image'];
        if (!empty($_FILES['image']['name'])) {
            $newImage = $this->uploadImage($_FILES['image']);
            if ($newImage) {
                if ($image && file_exists('../storage/uploads/' . $image)) {
                    unlink('../storage/uploads/' . $image);
                }
                $image = $newImage;
            }
        }
        
        $data = [
            'title' => trim($_POST['title']),
            'slug' => $this->postModel->generateSlug($_POST['title']),
            'excerpt' => trim($_POST['excerpt']),
            'content' => $_POST['content'],
            'category' => $_POST['category'] ?? 'news',
            'image' => $image,
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured']) ? 1 : 0
        ];
        
        try {
            $this->postModel->update($id, $data);
            $this->session->flash('success', 'Cập nhật thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi cập nhật: ' . $e->getMessage());
        }
        
        $this->redirect('/websitebatminton/admin/posts');
    }
    
    /**
     * Delete post
     */
    public function delete() {
        if (!$this->isPost()) {
            $this->redirect('/websitebatminton/admin/posts');
        }
        
        if (!$this->validateCsrf()) {
            $this->redirect('/websitebatminton/admin/posts');
        }
        
        $id = $_POST['id'] ?? 0;
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->session->flash('error', 'Bài viết không tồn tại');
            $this->redirect('/websitebatminton/admin/posts');
        }
        
        try {
            if ($post['image'] && file_exists('../storage/uploads/' . $post['image'])) {
                unlink('../storage/uploads/' . $post['image']);
            }
            $this->postModel->delete($id);
            $this->session->flash('success', 'Xóa thành công');
        } catch (Exception $e) {
            $this->session->flash('error', 'Lỗi xóa');
        }
        
        $this->redirect('/websitebatminton/admin/posts');
    }
    
    /**
     * Update status AJAX
     */
    public function status() {
        if (!$this->isPost()) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request']);
        }
        
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        
        try {
            $this->postModel->updateStatus($id, $status);
            $this->jsonResponse(['success' => true]);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Lỗi cập nhật']);
        }
    }
    
    /**
     * Validate post input
     */
    private function validatePost($data) {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = 'Tiêu đề không được để trống';
        }
        
        if (empty($data['content'])) {
            $errors[] = 'Nội dung không được để trống';
        }
        
        return $errors;
    }
    
    /**
     * Upload image (copy from ProductController)
     */
    private function uploadImage($file) {
        if (empty($file['name'])) {
            return null;
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return null;
        }
        
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return null;
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('post_') . '.' . $extension;
        
        $uploadDir = '../storage/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return $filename;
        }
        
        return null;
    }
}

