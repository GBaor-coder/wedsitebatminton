<!-- Product Form View -->
<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-box me-2"></i>
            <?php echo $product ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới'; ?>
        </h5>
    </div>
    
    <div class="card-body">
        <form method="POST" action="<?php echo $product ? '/websitebatminton/admin/products/update' : '/websitebatminton/admin/products/store'; ?>" 
              enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <?php if ($product): ?>
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo $product['name'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                        <?php echo ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo $cat['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="<?php echo $product['price'] ?? ''; ?>" min="0" step="1000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" 
                                       value="<?php echo $product['sale_price'] ?? ''; ?>" min="0" step="1000">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantity" name="quantity" 
                               value="<?php echo $product['quantity'] ?? 0; ?>" min="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="5"><?php echo $product['description'] ?? ''; ?></textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png">
                        <small class="text-muted">Chỉ chấp nhận JPG, PNG. Max 2MB</small>
                        
                        <?php if ($product && $product['image']): ?>
                        <div class="mt-2">
                            <img src="/websitebatminton/storage/uploads/<?php echo $product['image']; ?>" 
                                 alt="<?php echo $product['name']; ?>" 
                                 class="img-thumbnail" style="max-width: 200px;">
                            <p class="text-muted small mt-1">Ảnh hiện tại</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?php echo ($product['status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>
                                Hoạt động
                            </option>
                            <option value="inactive" <?php echo ($product['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>
                                Ẩn
                            </option>
                        </select>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="featured" name="featured" 
                               <?php echo ($product['featured'] ?? 0) == 1 ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="featured">Sản phẩm nổi bật</label>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="/websitebatminton/admin/products" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu
                </button>
            </div>
        </form>
    </div>
</div>

