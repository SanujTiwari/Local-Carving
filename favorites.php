<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/localcarving";

// Ensure only normal users can access this page
requireUser();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle adding a favorite
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_favorite'])) {
    $restaurant_id = (int)$_POST['restaurant_id'];
    
    // Check if restaurant exists
    $stmt = $pdo->prepare("SELECT id FROM restaurants WHERE id = ?");
    $stmt->execute([$restaurant_id]);
    
    if ($stmt->rowCount() > 0) {
        // Check if already favorited
        $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND restaurant_id = ?");
        $stmt->execute([$user_id, $restaurant_id]);
        
        if ($stmt->rowCount() === 0) {
            // Add to favorites
            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, restaurant_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $restaurant_id]);
            
            // Log the activity
            $stmt = $pdo->prepare("
                INSERT INTO activity_log (user_id, action, description, ip_address)
                VALUES (?, 'add_favorite', ?, ?)
            ");
            $description = "Added restaurant ID: $restaurant_id to favorites";
            $ip = $_SERVER['REMOTE_ADDR'];
            $stmt->execute([$user_id, $description, $ip]);
            
            $success = "Restaurant added to favorites!";
        } else {
            $error = "Restaurant is already in your favorites.";
        }
    } else {
        $error = "Restaurant not found.";
    }
}

// Handle removing a favorite
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_favorite'])) {
    $restaurant_id = (int)$_POST['restaurant_id'];
    
    // Remove from favorites
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND restaurant_id = ?");
    $stmt->execute([$user_id, $restaurant_id]);
    
    if ($stmt->rowCount() > 0) {
        // Log the activity
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (user_id, action, description, ip_address)
            VALUES (?, 'remove_favorite', ?, ?)
        ");
        $description = "Removed restaurant ID: $restaurant_id from favorites";
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt->execute([$user_id, $description, $ip]);
        
        $success = "Restaurant removed from favorites.";
    } else {
        $error = "Restaurant was not in your favorites.";
    }
}

// Get user's favorites
$stmt = $pdo->prepare("
    SELECT f.*, r.name, r.logo_path, r.address, r.city, r.category
    FROM favorites f
    JOIN restaurants r ON f.restaurant_id = r.id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
");
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="mb-4">My Favorite Restaurants</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (empty($favorites)): ?>
        <div class="alert alert-info">
            <p>You haven't added any restaurants to your favorites yet.</p>
            <a href="../restaurants.php" class="btn btn-primary mt-2">Browse Restaurants</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($favorites as $favorite): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if ($favorite['logo_path']): ?>
                            <img src="<?php echo $base_url; ?>/<?php echo htmlspecialchars($favorite['logo_path']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($favorite['name']); ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($favorite['name']); ?></h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($favorite['address']); ?>, 
                                <?php echo htmlspecialchars($favorite['city']); ?>
                            </p>
                            <p class="mb-2">
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($favorite['category']); ?></span>
                            </p>
                            <p class="text-muted small mb-3">
                                Added on <?php echo date('F j, Y', strtotime($favorite['created_at'])); ?>
                            </p>
                            
                            <div class="d-flex justify-content-between">
                                <a href="../restaurant.php?id=<?php echo $favorite['restaurant_id']; ?>" class="btn btn-primary">
                                    View Restaurant
                                </a>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="remove_favorite" value="1">
                                    <input type="hidden" name="restaurant_id" value="<?php echo $favorite['restaurant_id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?> 