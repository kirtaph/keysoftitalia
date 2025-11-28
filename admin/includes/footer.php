        </main>
        
        <footer class="bg-white text-center py-3 border-top mt-auto">
            <div class="container-fluid">
                <small class="text-muted">
                    &copy; <?php echo date('Y'); ?> Key Soft Italia Admin Panel. All rights reserved.
                </small>
            </div>
        </footer>
    </div> <!-- End admin-main -->
</div> <!-- End admin-wrapper -->

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Admin JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        }
    });
});
</script>

</body>
</html>