<?php
require_once 'session.php'; 
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/localcarving";

// Start output buffering if not already started
if (ob_get_level() === 0) {
    ob_start();
}
?>
    </main>
    <footer class="footer mt-auto py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-dark mb-3">LocalCarving</h5>
                    <p class="text-muted">Discover and order from the best local restaurants in your area.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-dark mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <?php if (isLoggedIn()): ?>
                            <?php if (isOwner()): ?>
                                <li><a href="<?php echo $base_url; ?>/owner/dashboard.php" class="text-muted text-decoration-none">Dashboard</a></li>
                                <li><a href="<?php echo $base_url; ?>/owner/manage-orders.php" class="text-muted text-decoration-none">Orders</a></li>
                                <li><a href="<?php echo $base_url; ?>/owner/reviews.php" class="text-muted text-decoration-none">Reviews</a></li>
                                <li><a href="<?php echo $base_url; ?>/about.php" class="text-muted text-decoration-none">About</a></li>
                            <?php else: ?>
                                <li><a href="<?php echo $base_url; ?>/user/dashboard.php" class="text-muted text-decoration-none">Dashboard</a></li>
                                <li><a href="<?php echo $base_url; ?>/user/orders.php" class="text-muted text-decoration-none">My Orders</a></li>
                                <li><a href="<?php echo $base_url; ?>/user/favorites.php" class="text-muted text-decoration-none">Favorites</a></li>
                                <li><a href="<?php echo $base_url; ?>/about.php" class="text-muted text-decoration-none">About</a></li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li><a href="<?php echo $base_url; ?>/index.php" class="text-muted text-decoration-none">Home</a></li>
                            <li><a href="<?php echo $base_url; ?>/restaurants.php" class="text-muted text-decoration-none">Restaurants</a></li>
                            <li><a href="<?php echo $base_url; ?>/about.php" class="text-muted text-decoration-none">About</a></li>
                            <li><a href="<?php echo $base_url; ?>/login.php" class="text-muted text-decoration-none">Login</a></li>
                            <li><a href="<?php echo $base_url; ?>/register.php" class="text-muted text-decoration-none">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-dark mb-3">Contact Us</h5>
                    <ul class="list-unstyled text-muted">
                        <li><i class="bi bi-envelope me-2"></i> support@localcarving.com</li>
                        <li><i class="bi bi-telephone me-2"></i> +1 (555) 123-4567</li>
                        <li><i class="bi bi-geo-alt me-2"></i> 123 Food Street, Cuisine City</li>
                    </ul>
                    <div class="mt-3">
                        <a href="#" class="text-muted me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-muted me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-muted me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <small>&copy; <?php echo date('Y'); ?> LocalCarving. All rights reserved.</small>
            </div>
        </div>
    </footer>
    
    <!-- Include chatbot icon for logged-in users -->
    <?php if (isLoggedIn()): ?>
        <?php require_once 'chatbot-icon.php'; ?>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Flush the output buffer
if (ob_get_level() > 0) {
    ob_end_flush();
}
?> 