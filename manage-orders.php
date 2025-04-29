<?php
// Start output buffering
ob_start();

require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure only restaurant owners can access this page
requireOwner();

// Get the owner's restaurants
$owner_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, name FROM restaurants WHERE owner_id = ?");
$stmt->execute([$owner_id]);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get filters
$restaurant_id = $_GET['restaurant'] ?? 'all';
$status = $_GET['status'] ?? 'all';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// Base query
$query = "
    SELECT o.*, 
           r.name as restaurant_name,
           u.username as customer_name
    FROM orders o
    JOIN restaurants r ON o.restaurant_id = r.id
    JOIN users u ON o.user_id = u.id
    WHERE r.owner_id = ?
";
$params = [$owner_id];

// Apply filters
if ($restaurant_id !== 'all') {
    $query .= " AND o.restaurant_id = ?";
    $params[] = $restaurant_id;
}

if ($status !== 'all') {
    $query .= " AND o.status = ?";
    $params[] = $status;
}

if ($from_date) {
    $query .= " AND DATE(o.created_at) >= ?";
    $params[] = $from_date;
}

if ($to_date) {
    $query .= " AND DATE(o.created_at) <= ?";
    $params[] = $to_date;
}

$query .= " ORDER BY o.created_at DESC";

// Fetch orders
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $order_id = $_POST['order_id'] ?? null;
    $new_status = $_POST['new_status'] ?? null;
    
    if ($order_id && $new_status) {
        // Verify the order belongs to one of the owner's restaurants
        $stmt = $pdo->prepare("
            SELECT o.id, o.status
            FROM orders o 
            JOIN restaurants r ON o.restaurant_id = r.id 
            WHERE o.id = ? AND r.owner_id = ?
        ");
        $stmt->execute([$order_id, $owner_id]);
        $order = $stmt->fetch();
        
        if ($order) {
            // Define valid status transitions
            $valid_transitions = [
                'pending' => ['confirmed', 'cancelled'],
                'confirmed' => ['preparing', 'cancelled'],
                'preparing' => ['ready', 'cancelled'],
                'ready' => ['delivered'],
                'delivered' => [], // No transitions allowed from delivered
                'cancelled' => [] // No transitions allowed from cancelled
            ];
            
            // Check if the status transition is valid
            if (in_array($new_status, $valid_transitions[$order['status']] ?? [])) {
                $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                $stmt->execute([$new_status, $order_id]);
                $_SESSION['success'] = "Order status updated successfully!";
            } else {
                $_SESSION['error'] = "Invalid status transition. This action is not allowed.";
            }
        }
    }
    
    header("Location: manage-orders.php?" . http_build_query($_GET));
    exit();
}

// Get success message
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>

<div class="container my-5">
    <h1 class="mb-4">Manage Orders</h1>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Restaurant</label>
                    <select name="restaurant" class="form-select">
                        <option value="all">All Restaurants</option>
                        <?php foreach ($restaurants as $r): ?>
                            <option value="<?php echo $r['id']; ?>" <?php echo $restaurant_id == $r['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($r['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="preparing" <?php echo $status === 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                        <option value="ready" <?php echo $status === 'ready' ? 'selected' : ''; ?>>Ready</option>
                        <option value="delivered" <?php echo $status === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo $from_date; ?>">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo $to_date; ?>">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Filter</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <!-- Orders Table -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($orders)): ?>
                <div class="alert alert-info">No orders found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Restaurant</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['restaurant_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
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
                                    </td>
                                    <td><?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary view-details" 
                                                    data-bs-toggle="modal" data-bs-target="#orderModal" 
                                                    data-order-id="<?php echo $order['id']; ?>">
                                                View Details
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split" 
                                                    data-bs-toggle="dropdown">
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if (!in_array($order['status'], ['delivered', 'cancelled'])): ?>
                                                    <?php if ($order['status'] === 'pending'): ?>
                                                        <li>
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="action" value="update_status">
                                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                                <input type="hidden" name="new_status" value="confirmed">
                                                                <button type="submit" class="dropdown-item">Confirm Order</button>
                                                            </form>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($order['status'] === 'confirmed'): ?>
                                                        <li>
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="action" value="update_status">
                                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                                <input type="hidden" name="new_status" value="preparing">
                                                                <button type="submit" class="dropdown-item">Start Preparing</button>
                                                            </form>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($order['status'] === 'preparing'): ?>
                                                        <li>
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="action" value="update_status">
                                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                                <input type="hidden" name="new_status" value="ready">
                                                                <button type="submit" class="dropdown-item">Mark as Ready</button>
                                                            </form>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($order['status'] === 'ready'): ?>
                                                        <li>
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="action" value="update_status">
                                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                                <input type="hidden" name="new_status" value="delivered">
                                                                <button type="submit" class="dropdown-item">Mark as Delivered</button>
                                                            </form>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!in_array($order['status'], ['ready', 'delivered', 'cancelled'])): ?>
                                                        <li>
                                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                                                <input type="hidden" name="action" value="update_status">
                                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                                <input type="hidden" name="new_status" value="cancelled">
                                                                <button type="submit" class="dropdown-item text-danger">Cancel Order</button>
                                                            </form>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetails">
                    <!-- Order details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle View Details button click
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            
            // Fetch order details
            fetch(`get-order-details.php?id=${orderId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('orderDetails').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('orderDetails').innerHTML = 'Error loading order details.';
                });
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>

<?php
// Flush the output buffer
ob_end_flush();
?>  