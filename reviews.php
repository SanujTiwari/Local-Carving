<?php
// Start output buffering at the very beginning
ob_start();

// Include required files
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Restrict access to normal users only
requireUser();

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $restaurant_id = $_POST['restaurant_id'] ?? 0;
    $rating = $_POST['rating'] ?? 0;
    $comment = trim($_POST['comment'] ?? '');
    
    // Validate inputs
    if (!$restaurant_id || !$rating || !$comment) {
        $_SESSION['error'] = "All fields are required.";
    } elseif ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = "Rating must be between 1 and 5.";
    } else {
        // Check if restaurant exists
        $stmt = $pdo->prepare("SELECT id FROM restaurants WHERE id = ?");
        $stmt->execute([$restaurant_id]);
        
        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = "Restaurant not found.";
        } else {
            // Check if user has already reviewed this restaurant
            $stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND restaurant_id = ?");
            $stmt->execute([$_SESSION['user_id'], $restaurant_id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = "You have already reviewed this restaurant.";
            } else {
                // Add the review
                try {
                    // Start transaction
                    $pdo->beginTransaction();
                    
                    // Add the review
                    $stmt = $pdo->prepare("
                        INSERT INTO reviews (user_id, restaurant_id, rating, comment)
                        VALUES (?, ?, ?, ?)
                    ");
                    $stmt->execute([$_SESSION['user_id'], $restaurant_id, $rating, $comment]);
                    
                    // Log the activity
                    $stmt = $pdo->prepare("
                        INSERT INTO activity_log (user_id, action, description, ip_address)
                        VALUES (?, 'add_review', ?, ?)
                    ");
                    $description = "Added a review for restaurant ID: $restaurant_id";
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $stmt->execute([$_SESSION['user_id'], $description, $ip]);
                    
                    // Commit transaction
                    $pdo->commit();
                    
                    $_SESSION['success'] = "Your review has been added successfully!";
                    
                    // Clean output buffer before redirect
                    ob_end_clean();
                    header("Location: ../restaurant.php?id=" . $restaurant_id);
                    exit();
                } catch (PDOException $e) {
                    // Rollback transaction on error
                    $pdo->rollBack();
                    $_SESSION['error'] = "An error occurred while adding your review.";
                }
            }
        }
    }
    
    // Clean output buffer before redirect
    ob_end_clean();
    header("Location: ../restaurant.php?id=" . $restaurant_id);
    exit();
}

// Handle review deletion
if (isset($_POST['delete_review']) && isset($_POST['review_id'])) {
    $review_id = $_POST['review_id'];
    
    // Verify the review belongs to the user
    $stmt = $pdo->prepare("SELECT restaurant_id FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$review_id, $_SESSION['user_id']]);
    $review = $stmt->fetch();
    
    if ($review) {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Delete the review
            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
            $stmt->execute([$review_id, $_SESSION['user_id']]);
            
            // Log the activity
            $stmt = $pdo->prepare("
                INSERT INTO activity_log (user_id, action, description, ip_address)
                VALUES (?, 'delete_review', ?, ?)
            ");
            $description = "Deleted review ID: $review_id";
            $ip = $_SERVER['REMOTE_ADDR'];
            $stmt->execute([$_SESSION['user_id'], $description, $ip]);
            
            // Commit transaction
            $pdo->commit();
            
            $_SESSION['success'] = "Review deleted successfully.";
        } catch (PDOException $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            $_SESSION['error'] = "An error occurred while deleting the review.";
        }
    }
    
    // Clean output buffer before redirect
    ob_end_clean();
    header('Location: reviews.php');
    exit();
}

// Get success/error messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Fetch user's reviews with restaurant details
$stmt = $pdo->prepare("
    SELECT r.*, res.name as restaurant_name, res.logo_path
    FROM reviews r
    JOIN restaurants res ON r.restaurant_id = res.id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">My Reviews</h2>
                <a href="../restaurants.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Write New Review
                </a>
            </div>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (empty($reviews)): ?>
                <div class="alert alert-info">
                    You haven't reviewed any restaurants yet.
                    <a href="../restaurants.php">Browse restaurants</a> to write your first review!
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($reviews as $review): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <?php if ($review['logo_path']): ?>
                                                <img src="<?php echo htmlspecialchars('../' . $review['logo_path']); ?>" 
                                                     class="rounded-circle me-3" 
                                                     alt="<?php echo htmlspecialchars($review['restaurant_name']); ?>"
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    <a href="../restaurant.php?id=<?php echo $review['restaurant_id']; ?>" 
                                                       class="text-decoration-none">
                                                        <?php echo htmlspecialchars($review['restaurant_name']); ?>
                                                    </a>
                                                </h5>
                                                <div class="text-warning mb-2">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                            <button type="submit" name="delete_review" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Reviewed on <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
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

<?php require_once '../includes/footer.php'; ?>

// End output buffering at the end of the file
ob_end_flush();

 