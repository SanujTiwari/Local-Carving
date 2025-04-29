<?php
// Start output buffering
ob_start();

require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/session.php';

// Ensure user is logged in
requireUser();

// Get restaurant ID from URL
$restaurant_id = isset($_GET['restaurant_id']) ? (int)$_GET['restaurant_id'] : 0;

if ($restaurant_id <= 0) {
    header("Location: restaurants.php");
    exit;
}

// Get restaurant details
$stmt = $pdo->prepare("
    SELECT r.*, u.username as owner_name
    FROM restaurants r
    JOIN users u ON r.owner_id = u.id
    WHERE r.id = ?
");
$stmt->execute([$restaurant_id]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    header("Location: restaurants.php");
    exit;
}

// Get menu items
$stmt = $pdo->prepare("
    SELECT * FROM menu_items 
    WHERE restaurant_id = ?
    ORDER BY category, name
");
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

// Handle order submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Validate cart items
    if (!isset($_POST['items']) || empty($_POST['items'])) {
        $error = "Please select at least one item to order.";
    } else {
        $items = $_POST['items'];
        $quantities = $_POST['quantities'];
        $total_amount = 0;
        $order_items = [];
        
        // Calculate total and prepare order items
        foreach ($items as $item_id => $selected) {
            if ($selected && isset($quantities[$item_id]) && $quantities[$item_id] > 0) {
                $quantity = (int)$quantities[$item_id];
                
                // Get item price
                $stmt = $pdo->prepare("SELECT price FROM menu_items WHERE id = ? AND restaurant_id = ?");
                $stmt->execute([$item_id, $restaurant_id]);
                $price_result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($price_result) {
                    $price = $price_result['price'];
                    $total_amount += $price * $quantity;
                    $order_items[] = [
                        'menu_item_id' => $item_id,
                        'quantity' => $quantity,
                        'price' => $price
                    ];
                }
            }
        }
        
        if (empty($order_items)) {
            $error = "Please select at least one item to order.";
        } else {
            // Start transaction
            $pdo->beginTransaction();
            
            try {
                // Create order
                $stmt = $pdo->prepare("
                    INSERT INTO orders (user_id, restaurant_id, total_amount, status)
                    VALUES (?, ?, ?, 'pending')
                ");
                $user_id = $_SESSION['user_id'];
                $stmt->execute([$user_id, $restaurant_id, $total_amount]);
                $order_id = $pdo->lastInsertId();
                
                // Add order items
                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, menu_item_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ");
                
                foreach ($order_items as $item) {
                    $stmt->execute([$order_id, $item['menu_item_id'], $item['quantity'], $item['price']]);
                }
                
                // Log the activity
                $stmt = $pdo->prepare("
                    INSERT INTO activity_log (user_id, action, description, ip_address)
                    VALUES (?, 'place_order', ?, ?)
                ");
                $description = "Placed order #$order_id at " . $restaurant['name'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $stmt->execute([$user_id, $description, $ip]);
                
                // Commit transaction
                $pdo->commit();
                
                // Redirect to order details page
                header("Location: user/order-details.php?id=" . $order_id);
                exit;
            } catch (Exception $e) {
                // Rollback transaction on error
                $pdo->rollBack();
                $error = "An error occurred while placing your order. Please try again.";
            }
        }
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <?php if ($restaurant['logo_path']): ?>
                            <img src="<?php echo htmlspecialchars($restaurant['logo_path']); ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?>" class="me-3" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                        <?php endif; ?>
                        <div>
                            <h1 class="card-title mb-1"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($restaurant['category']); ?></p>
                            <p class="mb-0"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($restaurant['address']); ?></p>
                        </div>
                    </div>
                    
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($restaurant['description'])); ?></p>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($restaurant['phone']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($restaurant['email']); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><i class="bi bi-clock"></i> Open: <?php echo date('h:i A', strtotime($restaurant['opening_time'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="bi bi-clock"></i> Close: <?php echo date('h:i A', strtotime($restaurant['closing_time'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form method="POST" id="orderForm">
                <input type="hidden" name="place_order" value="1">
                
                <?php if (empty($menu_categories)): ?>
                    <div class="alert alert-info">No menu items available for this restaurant.</div>
                <?php else: ?>
                    <?php foreach ($menu_categories as $category => $items): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><?php echo htmlspecialchars($category); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($items as $item): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <?php if ($item['image_path']): ?>
                                                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>" style="height: 150px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                                        <div class="form-check">
                                                            <input class="form-check-input item-checkbox" type="checkbox" name="items[<?php echo $item['id']; ?>]" id="item<?php echo $item['id']; ?>" value="1">
                                                            <label class="form-check-label" for="item<?php echo $item['id']; ?>">Select</label>
                                                        </div>
                                                    </div>
                                                    <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-primary fw-bold">$<?php echo number_format($item['price'], 2); ?></span>
                                                        <div class="input-group" style="width: 120px;">
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease" data-item-id="<?php echo $item['id']; ?>">-</button>
                                                            <input type="number" class="form-control text-center quantity-input" name="quantities[<?php echo $item['id']; ?>]" value="0" min="0" data-item-id="<?php echo $item['id']; ?>" readonly>
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase" data-item-id="<?php echo $item['id']; ?>">+</button>
                                                        </div>
                                                    </div>
                                                    <?php if ($item['is_veg']): ?>
                                                        <span class="badge bg-success mt-2">Vegetarian</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <div id="orderSummary">
                        <p class="text-muted">Select items from the menu to place an order.</p>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" form="orderForm" class="btn btn-primary" id="placeOrderBtn" disabled>Place Order</button>
                        <a href="restaurant.php?id=<?php echo $restaurant_id; ?>" class="btn btn-outline-secondary">Back to Restaurant</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const orderSummary = document.getElementById('orderSummary');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    
    // Update order summary
    function updateOrderSummary() {
        let totalItems = 0;
        let totalAmount = 0;
        let summaryHTML = '';
        
        itemCheckboxes.forEach(checkbox => {
            const itemId = checkbox.getAttribute('id').replace('item', '');
            const quantityInput = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const quantity = parseInt(quantityInput.value);
            
            if (checkbox.checked && quantity > 0) {
                const itemName = checkbox.closest('.card').querySelector('.card-title').textContent;
                const itemPrice = parseFloat(checkbox.closest('.card').querySelector('.text-primary').textContent.replace('$', ''));
                const itemTotal = itemPrice * quantity;
                
                totalItems += quantity;
                totalAmount += itemTotal;
                
                summaryHTML += `
                    <div class="d-flex justify-content-between mb-2">
                        <span>${itemName} x ${quantity}</span>
                        <span>$${itemTotal.toFixed(2)}</span>
                    </div>
                `;
            }
        });
        
        if (totalItems > 0) {
            summaryHTML += `
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>$${totalAmount.toFixed(2)}</span>
                </div>
            `;
            placeOrderBtn.disabled = false;
        } else {
            summaryHTML = '<p class="text-muted">Select items from the menu to place an order.</p>';
            placeOrderBtn.disabled = true;
        }
        
        orderSummary.innerHTML = summaryHTML;
    }
    
    // Handle checkbox changes
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const itemId = this.getAttribute('id').replace('item', '');
            const quantityInput = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            
            if (this.checked) {
                quantityInput.value = 1;
            } else {
                quantityInput.value = 0;
            }
            
            updateOrderSummary();
        });
    });
    
    // Handle quantity button clicks
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const action = this.getAttribute('data-action');
            const quantityInput = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const checkbox = document.querySelector(`#item${itemId}`);
            
            let quantity = parseInt(quantityInput.value);
            
            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 0) {
                quantity--;
            }
            
            quantityInput.value = quantity;
            
            if (quantity > 0) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
            
            updateOrderSummary();
        });
    });
    
    // Handle quantity input changes
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.getAttribute('data-item-id');
            const checkbox = document.querySelector(`#item${itemId}`);
            
            let quantity = parseInt(this.value);
            if (isNaN(quantity) || quantity < 0) {
                quantity = 0;
                this.value = 0;
            }
            
            if (quantity > 0) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
            
            updateOrderSummary();
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>

<?php
// Flush the output buffer
ob_end_flush();
?> 