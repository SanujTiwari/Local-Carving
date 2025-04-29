<?php
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/session.php';

// Get restaurant ID
$restaurant_id = $_GET['id'] ?? null;

if (!$restaurant_id) {
    header('Location: restaurants.php');
    exit();
}

// Fetch restaurant details
$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->execute([$restaurant_id]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    header('Location: restaurants.php');
    exit();
}

// Fetch menu items
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE restaurant_id = ? ORDER BY category, name");
$stmt->execute([$restaurant_id]);
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group menu items by category
$menu_categories = [];
foreach ($menu_items as $item) {
    $category = $item['category'] ?: 'Uncategorized';
    if (!isset($menu_categories[$category])) {
        $menu_categories[$category] = [];
    }
    $menu_categories[$category][] = $item;
}

// Check if restaurant is currently open
$current_time = strtotime(date('H:i:s'));
$opening_time = strtotime($restaurant['opening_time']);
$closing_time = strtotime($restaurant['closing_time']);
$is_open = $current_time >= $opening_time && $current_time <= $closing_time;

// Check if user has favorited this restaurant
$is_favorited = false;
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND restaurant_id = ?");
    $stmt->execute([$_SESSION['user_id'], $restaurant_id]);
    $is_favorited = $stmt->rowCount() > 0;
}

// Get success/error messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="container my-5">
    <!-- Restaurant Header -->
    <div class="row mb-5">
        <div class="col-md-4">
            <?php if ($restaurant['logo_path']): ?>
                <img src="<?php echo htmlspecialchars($restaurant['logo_path']); ?>" 
                     alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                     class="img-fluid rounded shadow">
            <?php else: ?>
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                    <i class="bi bi-shop text-muted" style="font-size: 6rem;"></i>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-8">
            <h1 class="mb-3"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
            
            <div class="mb-3">
                <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($restaurant['category']); ?></span>
                <?php if ($is_open): ?>
                    <span class="badge bg-success">Open Now</span>
                <?php else: ?>
                    <span class="badge bg-danger">Closed</span>
                <?php endif; ?>
            </div>
            
            <p class="text-muted mb-3">
                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($restaurant['address']); ?>, 
                <?php echo htmlspecialchars($restaurant['city']); ?>
            </p>
            
            <p class="mb-3">
                <i class="bi bi-clock"></i> 
                <?php echo date('g:i A', strtotime($restaurant['opening_time'])); ?> - 
                <?php echo date('g:i A', strtotime($restaurant['closing_time'])); ?>
            </p>
            
            <?php if ($restaurant['phone']): ?>
                <p class="mb-3">
                    <i class="bi bi-telephone"></i> 
                    <a href="tel:<?php echo htmlspecialchars($restaurant['phone']); ?>" class="text-decoration-none">
                        <?php echo htmlspecialchars($restaurant['phone']); ?>
                    </a>
                </p>
            <?php endif; ?>
            
            <?php if ($restaurant['email']): ?>
                <p class="mb-3">
                    <i class="bi bi-envelope"></i> 
                    <a href="mailto:<?php echo htmlspecialchars($restaurant['email']); ?>" class="text-decoration-none">
                        <?php echo htmlspecialchars($restaurant['email']); ?>
                    </a>
                </p>
            <?php endif; ?>
            
            <?php if ($restaurant['description']): ?>
                <p class="mb-4"><?php echo nl2br(htmlspecialchars($restaurant['description'])); ?></p>
            <?php endif; ?>
            
            <div class="d-flex gap-2">
                <?php if (isLoggedIn()): ?>
                    <?php if ($is_favorited): ?>
                        <form action="user/favorites.php" method="POST" class="d-inline">
                            <input type="hidden" name="remove_favorite" value="1">
                            <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-heart-fill"></i> Remove from Favorites
                            </button>
                        </form>
                    <?php else: ?>
                        <form action="user/favorites.php" method="POST" class="d-inline">
                            <input type="hidden" name="add_favorite" value="1">
                            <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-heart"></i> Add to Favorites
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($is_open && !empty($menu_items)): ?>
                        <a href="place-order.php?restaurant_id=<?php echo $restaurant_id; ?>" class="btn btn-primary">
                            <i class="bi bi-cart-plus"></i> Order Now
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php?redirect=restaurant.php?id=<?php echo $restaurant_id; ?>" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Login to Order
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Menu Section -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Menu</h2>
            
            <?php if (empty($menu_items)): ?>
                <div class="alert alert-info">
                    No menu items available at the moment.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($menu_categories as $category => $items): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h3 class="h5 mb-0"><?php echo htmlspecialchars($category); ?></h3>
                                </div>
                                
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($items as $item): ?>
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <?php if ($item['image_path']): ?>
                                                        <div class="col-auto">
                                                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                                 class="rounded"
                                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="col">
                                                        <h5 class="mb-1">
                                                            <?php echo htmlspecialchars($item['name']); ?>
                                                            <?php if ($item['is_veg']): ?>
                                                                <span class="badge bg-success">Veg</span>
                                                            <?php endif; ?>
                                                        </h5>
                                                        
                                                        <?php if ($item['description']): ?>
                                                            <p class="text-muted small mb-0">
                                                                <?php echo htmlspecialchars($item['description']); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="col-auto">
                                                        <span class="h5 mb-0">$<?php echo number_format($item['price'], 2); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Reviews</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php
            // Fetch reviews
            $stmt = $pdo->prepare("
                SELECT r.*, u.username 
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.restaurant_id = ?
                ORDER BY r.created_at DESC
            ");
            $stmt->execute([$restaurant_id]);
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate average rating
            $total_rating = 0;
            $rating_count = count($reviews);
            foreach ($reviews as $review) {
                $total_rating += $review['rating'];
            }
            $avg_rating = $rating_count > 0 ? round($total_rating / $rating_count, 1) : 0;
            ?>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-4 text-center">
                            <h3 class="display-4 mb-0"><?php echo number_format($avg_rating, 1); ?></h3>
                            <div class="text-warning mb-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $avg_rating): ?>
                                        <i class="bi bi-star-fill"></i>
                                    <?php elseif ($i - 0.5 <= $avg_rating): ?>
                                        <i class="bi bi-star-half"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <p class="text-muted mb-0"><?php echo $rating_count; ?> review<?php echo $rating_count !== 1 ? 's' : ''; ?></p>
                        </div>
                        
                        <div class="flex-grow-1">
                            <?php
                            // Count reviews by rating
                            $rating_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                            foreach ($reviews as $review) {
                                $rating_counts[$review['rating']]++;
                            }
                            
                            // Display rating bars
                            for ($i = 5; $i >= 1; $i--):
                                $percentage = $rating_count > 0 ? round(($rating_counts[$i] / $rating_count) * 100) : 0;
                            ?>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="me-2" style="width: 60px;"><?php echo $i; ?> stars</div>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <div class="ms-2" style="width: 40px;"><?php echo $rating_counts[$i]; ?></div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (isLoggedIn()): ?>
                <?php
                // Check if user has already reviewed this restaurant
                $stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND restaurant_id = ?");
                $stmt->execute([$_SESSION['user_id'], $restaurant_id]);
                $has_reviewed = $stmt->rowCount() > 0;
                ?>
                
                <?php if (!$has_reviewed): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Write a Review</h5>
                            <form action="user/reviews.php" method="POST">
                                <input type="hidden" name="add_review" value="1">
                                <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating">
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                                            <label for="star<?php echo $i; ?>"><i class="bi bi-star-fill"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Comment</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> You have already reviewed this restaurant.
                        <a href="user/reviews.php">View your reviews</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle"></i> Please <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">log in</a> to write a review.
                </div>
            <?php endif; ?>
            
            <?php if (empty($reviews)): ?>
                <div class="alert alert-info">
                    No reviews yet. Be the first to review this restaurant!
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($reviews as $review): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($review['username']); ?></h5>
                                        <div class="text-warning">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin: 0 2px;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffc107;
}
</style>

<?php require_once 'includes/footer.php'; ?> 