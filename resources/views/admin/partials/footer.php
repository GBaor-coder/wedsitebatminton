                <!-- Footer -->
                <footer class="mt-5 py-3 border-top">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> JP Sport Admin Panel. All rights reserved.</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Custom JS -->
    <script src="/websitebatminton/assets/js/admin.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Confirm delete
        function confirmDelete(message = 'Bạn có chắc chắn muốn xóa không?') {
            return confirm(message);
        }
        
        // Status update via AJAX
        function updateStatus(url, id, status) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    id: id,
                    status: status,
                    csrf_token: '<?php echo $csrf_token ?? ''; ?>'
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            });
        }
    </script>
</body>
</html>

