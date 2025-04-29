<?php
ob_start();
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure only restaurant owners can access this page
requireOwner();

// Get owner's restaurants
$stmt = $pdo->prepare("
    SELECT id, name, logo_path 
    FROM restaurants 
    WHERE owner_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get selected restaurant
$selected_restaurant = $_GET['restaurant'] ?? 'all';
$rating_filter = $_GET['rating'] ?? 'all';
$sort_by = $_GET['sort'] ?? 'newest';

// Base query for reviews
$query = "
    SELECT r.*, 
           res.name as restaurant_name,
           res.logo_path,
           u.username as reviewer_name
    FROM reviews r
    JOIN restaurants res ON r.restaurant_id = res.id
    JOIN users u ON r.user_id = u.id
    WHERE res.owner_id = ?
";
$params = [$_SESSION['user_id']];

// Apply filters
if ($selected_restaurant !== 'all') {
    $query .= " AND r.restaurant_id = ?";
    $params[] = $selected_restaurant;
}

if ($rating_filter !== 'all') {
    $query .= " AND r.rating = ?";
    $params[] = $rating_filter;
}

// Apply sorting
$query .= match($sort_by) {
    'highest' => " ORDER BY r.rating DESC, r.created_at DESC",
    'lowest' => " ORDER BY r.rating ASC, r.created_at DESC",
    'oldest' => " ORDER BY r.created_at ASC",
    default => " ORDER BY r.created_at DESC" // newest first
};

// Fetch reviews
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate statistics
$stats = [];
foreach ($restaurants as $restaurant) {
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_reviews,
            AVG(rating) as avg_rating,
            COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
            COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
            COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
            COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
            COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
        FROM reviews
        WHERE restaurant_id = ?
    ");
    $stmt->execute([$restaurant['id']]);
    $stats[$restaurant['id']] = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Restaurant Reviews</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Restaurant</label>
                    <select name="restaurant" class="form-select" onchange="this.form.submit()">
                        <option value="all">All Restaurants</option>
                        <?php foreach ($restaurants as $restaurant): ?>
                            <option value="<?php echo $restaurant['id']; ?>" 
                                    <?php echo $selected_restaurant == $restaurant['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($restaurant['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-select" onchange="this.form.submit()">
                        <option value="all">All Ratings</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo $rating_filter == $i ? 'selected' : ''; ?>>
                                <?php echo $i; ?> Stars
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="newest" <?php echo $sort_by === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="oldest" <?php echo $sort_by === 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                        <option value="highest" <?php echo $sort_by === 'highest' ? 'selected' : ''; ?>>Highest Rated</option>
                        <option value="lowest" <?php echo $sort_by === 'lowest' ? 'selected' : ''; ?>>Lowest Rated</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <?php if ($selected_restaurant !== 'all'): ?>
        <?php
        $current_stats = $stats[$selected_restaurant];
        $total_reviews = $current_stats['total_reviews'];
        ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <h3 class="display-4 mb-0"><?php echo number_format($current_stats['avg_rating'], 1); ?></h3>
                        <div class="text-warning mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= round($current_stats['avg_rating'])): ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php elseif ($i - 0.5 <= $current_stats['avg_rating']): ?>
                                    <i class="bi bi-star-half"></i>
                                <?php else: ?>
                                    <i class="bi bi-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted mb-0"><?php echo $total_reviews; ?> review<?php echo $total_reviews !== 1 ? 's' : ''; ?></p>
                    </div>
                    <div class="col-md-9">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <?php 
                            $count = $current_stats["{$i}_star"];
                            $percentage = $total_reviews > 0 ? ($count / $total_reviews) * 100 : 0;
                            ?>
                            <div class="d-flex align-items-center mb-2">
                                <div style="width: 60px"><?php echo $i; ?> stars</div>
                                <div class="flex-grow-1 mx-2">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                </div>
                                <div style="width: 40px"><?php echo $count; ?></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Reviews List -->
    <?php if (empty($reviews)): ?>
        <div class="alert alert-info">
            No reviews found matching your criteria.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reviews as $review): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <?php if ($review['logo_path']): ?>
                                    <img src="<?php echo htmlspecialchars('../' . $review['logo_path']); ?>" 
                                         class="rounded-circle me-3" 
                                         alt="<?php echo htmlspecialchars($review['restaurant_name']); ?>"
                                         style="width: 48px; height: 48px; object-fit: cover;">
                                <?php endif; ?>
                                <div>
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($review['restaurant_name']); ?></h5>
                                    <div class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            <p class="card-text">
                                <small class="text-muted">
                                    By <?php echo htmlspecialchars($review['reviewer_name']); ?> on 
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

<?php 
require_once '../includes/footer.php';
ob_end_flush();
?> 