<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Fetch featured restaurants
$stmt = $pdo->query("SELECT * FROM restaurants ORDER BY created_at DESC LIMIT 6");
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Discover Local Food</h1>
                <p class="lead mb-4">Order from your favorite local restaurants and discover new flavors in your neighborhood.</p>
                <?php if (!isLoggedIn()): ?>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="register.php" class="btn btn-light btn-lg px-4 me-md-2">Get Started</a>
                        <a href="restaurants.php" class="btn btn-outline-light btn-lg px-4">Browse Restaurants</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="assets\images\hero_food.jpg" alt="Local Food" class="img-fluid rounded-3 shadow" style="max-width: 80%; height: auto; margin: 0 auto; display: block;">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose LocalCarving?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-shop display-4 text-primary mb-3"></i>
                        <h3 class="h5 mb-3">Local Restaurants</h3>
                        <p class="text-muted mb-0">Discover and order from the best local restaurants in your area.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-clock-history display-4 text-primary mb-3"></i>
                        <h3 class="h5 mb-3">Quick Delivery</h3>
                        <p class="text-muted mb-0">Fast and reliable delivery service right to your doorstep.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-star display-4 text-primary mb-3"></i>
                        <h3 class="h5 mb-3">Best Reviews</h3>
                        <p class="text-muted mb-0">Read reviews and ratings from real customers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (isLoggedIn()): ?>
    <?php if (isOwner()): ?>
        <!-- Owner Dashboard Preview -->
        <section class="bg-light py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="mb-4">Manage Your Restaurant</h2>
                        <p class="lead mb-4">Take control of your restaurant's online presence and manage orders efficiently.</p>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="owner/dashboard.php" class="btn btn-primary btn-lg px-4">Go to Dashboard</a>
                            <a href="owner/add-restaurant.php" class="btn btn-outline-primary btn-lg px-4">Add Restaurant</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-cart-check display-4 text-primary mb-3"></i>
                                        <h3 class="h5">Manage Orders</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-star display-4 text-primary mb-3"></i>
                                        <h3 class="h5">View Reviews</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-graph-up display-4 text-primary mb-3"></i>
                                        <h3 class="h5">Analytics</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-gear display-4 text-primary mb-3"></i>
                                        <h3 class="h5">Settings</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php else: ?>
        <!-- User Dashboard Preview -->
        <section class="bg-light py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="mb-4">Your Food Journey</h2>
                        <p class="lead mb-4">Track your orders, save your favorites, and discover new restaurants.</p>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="user/dashboard.php" class="btn btn-primary btn-lg px-4">Go to Dashboard</a>
                            <a href="restaurants.php" class="btn btn-outline-primary btn-lg px-4">Browse Restaurants</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-bag display-4 text-primary mb-3"></i>
                                        <h3 class="h5">My Orders</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-heart display-4 text-primary mb-3"></i>
                                        <h3 class="h5">Favorites</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-star display-4 text-primary mb-3"></i>
                                        <h3 class="h5">My Reviews</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-person display-4 text-primary mb-3"></i>
                                        <h3 class="h5">Profile</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php else: ?>
    <!-- How It Works Section -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-search display-4"></i>
                        </div>
                        <h3 class="h5 mb-3">1. Find Restaurants</h3>
                        <p class="text-muted">Browse through local restaurants and discover new places to eat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-cart-plus display-4"></i>
                        </div>
                        <h3 class="h5 mb-3">2. Place Order</h3>
                        <p class="text-muted">Select your favorite dishes and place your order with ease.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-truck display-4"></i>
                        </div>
                        <h3 class="h5 mb-3">3. Get Delivery</h3>
                        <p class="text-muted">Enjoy your food delivered right to your doorstep.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Restaurants Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Popular Restaurants</h2>
            <div class="row g-4">
                <?php
                // Fetch popular restaurants
                $stmt = $pdo->query("
                    SELECT r.*, 
                           COUNT(DISTINCT o.id) as order_count,
                           COUNT(DISTINCT rv.id) as review_count,
                           AVG(rv.rating) as avg_rating
                    FROM restaurants r
                    LEFT JOIN orders o ON r.id = o.restaurant_id
                    LEFT JOIN reviews rv ON r.id = rv.restaurant_id
                    GROUP BY r.id
                    ORDER BY order_count DESC, avg_rating DESC
                    LIMIT 3
                ");
                $popular_restaurants = $stmt->fetchAll();
                
                foreach ($popular_restaurants as $restaurant):
                ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <?php if ($restaurant['logo_path']): ?>
                            <img src="<?php echo htmlspecialchars($restaurant['logo_path']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="bi bi-shop display-1 text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3 class="h5 card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                            <p class="text-muted mb-2"><?php echo htmlspecialchars($restaurant['category']); ?></p>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning me-2">
                                    <?php
                                    $rating = round($restaurant['avg_rating'] ?? 0);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? 
                                            '<i class="bi bi-star-fill"></i>' : 
                                            '<i class="bi bi-star"></i>';
                                    }
                                    ?>
                                </div>
                                <span class="text-muted">(<?php echo $restaurant['review_count']; ?> reviews)</span>
                            </div>
                            <a href="restaurant.php?id=<?php echo $restaurant['id']; ?>" 
                               class="btn btn-outline-primary">View Menu</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="restaurants.php" class="btn btn-primary">View All Restaurants</a>
            </div>
        </div>
    </section>

    <!-- Restaurant Categories Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Explore by Category</h2>
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <a href="restaurants.php?category=Street+Food" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-bicycle display-4 text-primary mb-3"></i>
                                <h3 class="h5 text-dark">Street Food</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="restaurants.php?category=Café" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-cup-straw display-4 text-primary mb-3"></i>
                                <h3 class="h5 text-dark">Cafe</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="restaurants.php?category=Restaurant" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-shop display-4 text-primary mb-3"></i>
                                <h3 class="h5 text-dark">Restaurant</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="restaurants.php?category=Fast+Food" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-lightning display-4 text-primary mb-3"></i>
                                <h3 class="h5 text-dark">Fast Food</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">What Our Customers Say</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="text-muted">5.0</span>
                            </div>
                            <p class="card-text">"LocalCarving has completely changed how I order food. The variety of local restaurants is amazing, and the delivery is always on time!"</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Sarah Johnson</h5>
                                    <small class="text-muted">Food Enthusiast</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                                <span class="text-muted">4.5</span>
                            </div>
                            <p class="card-text">"As a busy professional, I love how easy it is to order from my favorite local restaurants. The app is intuitive and the food is always delicious!"</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Michael Chen</h5>
                                    <small class="text-muted">Business Professional</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="text-muted">5.0</span>
                            </div>
                            <p class="card-text">"I've discovered so many amazing local restaurants through LocalCarving. The reviews are helpful, and I love supporting local businesses!"</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Emily Rodriguez</h5>
                                    <small class="text-muted">Food Blogger</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Restaurant Owner Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-4">Are You a Restaurant Owner?</h2>
                    <p class="lead mb-4">Join LocalCarving and reach thousands of potential customers in your area. Manage your menu, track orders, and grow your business.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i> Easy menu management</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i> Real-time order tracking</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i> Customer reviews and ratings</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i> Analytics and insights</li>
                    </ul>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="register.php?role=owner" class="btn btn-primary btn-lg px-4">Register as Owner</a>
                        <a href="owner/add-restaurant.php" class="btn btn-outline-primary btn-lg px-4">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="assets\images\restaurent-owner.jpg" alt="Restaurant Owner" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Ready to Get Started?</h2>
        <p class="lead mb-4">Join LocalCarving today and discover the best local food in your area.</p>
        <?php if (!isLoggedIn()): ?>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="register.php" class="btn btn-light btn-lg px-4 me-sm-3">Sign Up Now</a>
                <a href="restaurants.php" class="btn btn-outline-light btn-lg px-4">Browse Restaurants</a>
            </div>
        <?php else: ?>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <?php if (isOwner()): ?>
                    <a href="owner/dashboard.php" class="btn btn-light btn-lg px-4">Go to Dashboard</a>
                <?php else: ?>
                    <a href="restaurants.php" class="btn btn-light btn-lg px-4">Order Now</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 