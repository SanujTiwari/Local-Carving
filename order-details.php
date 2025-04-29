<?php
ob_start();
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is logged in
requireLogin();

$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    header('Location: orders.php');
    exit();
}

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, 
           r.name as restaurant_name,
           r.logo_path as restaurant_logo,
           r.address as restaurant_address,
           r.phone as restaurant_phone
    FROM orders o
    JOIN restaurants r ON o.restaurant_id = r.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: orders.php');
    exit();
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, mi.name as item_name, mi.is_veg
    FROM order_items oi
    JOIN menu_items mi ON oi.menu_item_id = mi.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel_order') {
    // Define valid statuses for cancellation
    $cancellable_statuses = ['pending', 'confirmed'];
    
    if (in_array($order['status'], $cancellable_statuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        $_SESSION['success'] = "Order has been cancelled successfully.";
        header('Location: orders.php');
        exit();
    } else {
        if ($order['status'] === 'delivered') {
            $_SESSION['error'] = "Cannot cancel a delivered order.";
        } elseif ($order['status'] === 'cancelled') {
            $_SESSION['error'] = "This order has already been cancelled.";
        } else {
            $_SESSION['error'] = "Order cannot be cancelled. It is already in " . $order['status'] . " status.";
        }
    }
}

// Get any error messages
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Order Details</h1>
                <a href="orders.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Orders
                </a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <?php if ($order['restaurant_logo']): ?>
                            <img src="<?php echo htmlspecialchars('../' . $order['restaurant_logo']); ?>" 
                                 alt="<?php echo htmlspecialchars($order['restaurant_name']); ?>"
                                 class="rounded-circle me-3"
                                 style="width: 64px; height: 64px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h2 class="h4 mb-1"><?php echo htmlspecialchars($order['restaurant_name']); ?></h2>
                            <p class="mb-0 text-muted">
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($order['restaurant_address']); ?><br>
                                <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($order['restaurant_phone']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Order Information</h6>
                            <p class="mb-1"><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
                            <p class="mb-1"><strong>Date:</strong> <?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></p>
                            <p class="mb-1">
                                <strong>Status:</strong>
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
                                ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <h6 class="mb-3">Order Items</h6>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td>
                                            <?php if ($item['is_veg']): ?>
                                                <span class="badge bg-success">Veg</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Non-Veg</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-end">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php if (in_array($order['status'], ['pending', 'confirmed'])): ?>
                        <div class="alert alert-warning">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="alert-heading mb-1">Need to Cancel?</h6>
                                    <p class="mb-0">You can still cancel this order as it hasn't been prepared yet.</p>
                                </div>
                                <form method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');">
                                    <input type="hidden" name="action" value="cancel_order">
                                    <button type="submit" class="btn btn-outline-danger">Cancel Order</button>
                                </form>
                            </div>
                        </div>
                    <?php elseif ($order['status'] === 'preparing'): ?>
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-1">Order in Preparation</h6>
                            <p class="mb-0">This order is being prepared and can no longer be cancelled.</p>
                        </div>
                    <?php elseif ($order['status'] === 'ready'): ?>
                        <div class="alert alert-success">
                            <h6 class="alert-heading mb-1">Order Ready</h6>
                            <p class="mb-0">Your order is ready for pickup/delivery!</p>
                        </div>
                    <?php elseif ($order['status'] === 'delivered'): ?>
                        <div class="alert alert-secondary">
                            <h6 class="alert-heading mb-1">Order Delivered</h6>
                            <p class="mb-0">This order has been delivered successfully.</p>
                        </div>
                    <?php elseif ($order['status'] === 'cancelled'): ?>
                        <div class="alert alert-danger">
                            <h6 class="alert-heading mb-1">Order Cancelled</h6>
                            <p class="mb-0">This order has been cancelled.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require_once '../includes/footer.php';
ob_end_flush();
?> 