<?php
ob_start();
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is logged in
requireLogin();

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel_order') {
    $order_id = $_POST['order_id'] ?? null;
    
    if ($order_id) {
        // Verify the order belongs to the user and is in a cancellable state
        $stmt = $pdo->prepare("
            SELECT id, status 
            FROM orders 
            WHERE id = ? AND user_id = ? AND status IN ('pending', 'confirmed')
        ");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        $order = $stmt->fetch();
        
        if ($order) {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$order_id]);
            $_SESSION['success'] = "Order has been cancelled successfully.";
        } else {
            $_SESSION['error'] = "Order cannot be cancelled. It may be already in preparation.";
        }
    }
    
    header("Location: orders.php");
    exit();
}

// Get user's orders
$stmt = $pdo->prepare("
    SELECT o.*, 
           r.name as restaurant_name,
           r.logo_path as restaurant_logo
    FROM orders o
    JOIN restaurants r ON o.restaurant_id = r.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get success/error messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="container my-5">
    <h1 class="mb-4">My Orders</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            You haven't placed any orders yet. 
            <a href="../restaurants.php" class="alert-link">Browse restaurants</a> to place your first order!
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($orders as $order): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <?php if ($order['restaurant_logo']): ?>
                                    <img src="<?php echo htmlspecialchars('../' . $order['restaurant_logo']); ?>" 
                                         alt="<?php echo htmlspecialchars($order['restaurant_name']); ?>"
                                         class="rounded-circle me-3"
                                         style="width: 48px; height: 48px; object-fit: cover;">
                                <?php endif; ?>
                                <div>
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($order['restaurant_name']); ?></h5>
                                    <small class="text-muted">
                                        Order #<?php echo $order['id']; ?> • 
                                        <?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-<?php 
                                    echo match($order['status']) {
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'preparing' => 'primary',
                                        'ready' => 'success',
                                        'delivered' => 'secondary',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                ?> fs-6">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                                <span class="fs-5">$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            
                            <?php
                            // Get order items
                            $stmt = $pdo->prepare("
                                SELECT oi.*, mi.name as item_name
                                FROM order_items oi
                                JOIN menu_items mi ON oi.menu_item_id = mi.id
                                WHERE oi.order_id = ?
                            ");
                            $stmt->execute([$order['id']]);
                            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            
                            <div class="mb-3">
                                <h6 class="mb-2">Order Items:</h6>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($items as $item): ?>
                                        <li>
                                            <?php echo $item['quantity']; ?>x 
                                            <?php echo htmlspecialchars($item['item_name']); ?>
                                            <span class="text-muted">
                                                ($<?php echo number_format($item['price'], 2); ?> each)
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="order-details.php?id=<?php echo $order['id']; ?>" 
                                   class="btn btn-outline-primary">
                                    View Details
                                </a>
                                
                                <?php if (in_array($order['status'], ['pending', 'confirmed'])): ?>
                                    <form method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <input type="hidden" name="action" value="cancel_order">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger">Cancel Order</button>
                                    </form>
                                <?php endif; ?>
                            </div>
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