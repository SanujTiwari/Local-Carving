<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Restrict access to normal users only
requireUser();

// Fetch user's favorite restaurants
$stmt = $pdo->prepare("
    SELECT r.*, f.created_at as favorited_at 
    FROM restaurants r 
    JOIN favorites f ON r.id = f.restaurant_id 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$favorite_restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's recent orders
$stmt = $pdo->prepare("
    SELECT o.*, r.name as restaurant_name 
    FROM orders o 
    JOIN restaurants r ON o.restaurant_id = r.id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC 
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">My Orders</h5>
                            <p class="card-text">View and track your orders</p>
                            <a href="orders.php" class="btn btn-primary">View Orders</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Favorites</h5>
                            <p class="card-text">Manage your favorite restaurants</p>
                            <a href="favorites.php" class="btn btn-primary">View Favorites</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">My Reviews</h5>
                            <p class="card-text">View and manage your reviews</p>
                            <a href="reviews.php" class="btn btn-primary">View Reviews</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Browse Restaurants</h5>
                            <p class="card-text">Discover new restaurants</p>
                            <a href="../restaurants.php" class="btn btn-primary">Browse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="m-0">Favorite Restaurants</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($favorite_restaurants)): ?>
                        <p class="text-muted">You haven't favorited any restaurants yet.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($favorite_restaurants as $restaurant): ?>
                                <a href="../restaurant.php?id=<?php echo $restaurant['id']; ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                                        <small class="text-muted"><?php echo htmlspecialchars($restaurant['category']); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo htmlspecialchars($restaurant['address']); ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="m-0">Recent Orders</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_orders)): ?>
                        <p class="text-muted">You haven't placed any orders yet.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recent_orders as $order): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?php echo htmlspecialchars($order['restaurant_name']); ?></h5>
                                        <small class="text-muted"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-1">Order #<?php echo $order['id']; ?></p>
                                    <p class="mb-1">Total: $<?php echo number_format($order['total_amount'], 2); ?></p>
                                    <small class="text-muted">Status: <?php echo ucfirst($order['status']); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 