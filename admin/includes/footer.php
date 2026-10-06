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
<script src="../assets/js/pages/admin-tables.js"></script>
<script src="../assets/js/pages/admin-notifications.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const requested = new URLSearchParams(location.search).get('open');
    if (/^[1-9]\d*$/.test(requested || '')) {
        const target = document.querySelector(`.view-btn[data-id="${requested}"], .edit-btn[data-id="${requested}"]`);
        if (target) setTimeout(() => target.click(), 0);
    }
    if (location.hash === '#requests-panel') {
        const requestsTab = document.getElementById('requests-tab');
        if (requestsTab) setTimeout(() => requestsTab.click(), 0);
    }
    // Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    function syncSidebar() {
        const hidden = window.innerWidth < 992 && !sidebar.classList.contains('show');
        sidebar.inert = hidden;
        sidebarToggle.setAttribute('aria-expanded', String(!hidden));
    }
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            sidebarToggle.setAttribute('aria-expanded', String(sidebar.classList.contains('show')));
            syncSidebar();
        });
    }
    if (sidebar && sidebarToggle) { syncSidebar(); window.addEventListener('resize', syncSidebar); }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show'); syncSidebar(); sidebarToggle.focus();
        }
    });
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                sidebarToggle.setAttribute('aria-expanded', 'false');
                syncSidebar();
            }
        }
    });
});
</script>

</body>
</html>
