<?php
require_once '../includes/db.php';
require_once '../includes/session.php';

// Restrict access to owners only
requireOwner();

$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    echo 'Invalid order ID';
    exit;
}

// Verify the order belongs to one of the owner's restaurants
$stmt = $pdo->prepare("
    SELECT o.*, 
           r.name as restaurant_name,
           u.username as customer_name,
           u.email as customer_email
    FROM orders o
    JOIN restaurants r ON o.restaurant_id = r.id
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND r.owner_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo 'Order not found or access denied';
    exit;
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
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h6 class="mb-3">Customer Information</h6>
        <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
    </div>
    <div class="col-md-6">
        <h6 class="mb-3">Order Information</h6>
        <p class="mb-1"><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
        <p class="mb-1"><strong>Restaurant:</strong> <?php echo htmlspecialchars($order['restaurant_name']); ?></p>
        <p class="mb-1"><strong>Date:</strong> <?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></p>
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
<div class="table-responsive">
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

<?php if ($order['status'] !== 'cancelled'): ?>
    <div class="mt-4">
        <h6 class="mb-3">Update Order Status</h6>
        <form method="POST" action="manage-orders.php" class="row g-2">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
            
            <div class="col-auto">
                <select name="new_status" class="form-select" required>
                    <option value="">Select Status</option>
                    <?php if ($order['status'] === 'pending'): ?>
                        <option value="confirmed">Confirm Order</option>
                    <?php endif; ?>
                    <?php if ($order['status'] === 'confirmed'): ?>
                        <option value="preparing">Start Preparing</option>
                    <?php endif; ?>
                    <?php if ($order['status'] === 'preparing'): ?>
                        <option value="ready">Mark as Ready</option>
                    <?php endif; ?>
                    <?php if ($order['status'] === 'ready'): ?>
                        <option value="delivered">Mark as Delivered</option>
                    <?php endif; ?>
                    <option value="cancelled">Cancel Order</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
    </div>
<?php endif; ?> 