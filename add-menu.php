<?php
// Start output buffering
ob_start();

require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Restrict access to owners only
requireOwner();

// Initialize variables
$error = '';
$success = '';
$restaurant = null;
$menu_items = [];

// Get restaurant ID from URL
$restaurant_id = $_GET['res_id'] ?? null;

if (!$restaurant_id) {
    header('Location: dashboard.php');
    exit();
}

// Fetch restaurant details
$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = ? AND owner_id = ?");
$stmt->execute([$restaurant_id, $_SESSION['user_id']]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    header('Location: dashboard.php');
    exit();
}

// Fetch menu items
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE restaurant_id = ? ORDER BY category, name");
$stmt->execute([$restaurant_id]);
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $item_id = $_POST['item_id'] ?? null;
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category = $_POST['category'] ?? '';
            $is_veg = isset($_POST['is_veg']) ? 1 : 0;
            
            // Validation
            if (empty($name) || empty($price)) {
                $_SESSION['error'] = 'Please fill in all required fields';
            } else {
                $image_path = null;
                
                // Handle file upload if provided
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    $file_type = $_FILES['image']['type'];
                    
                    if (!in_array($file_type, $allowed_types)) {
                        $_SESSION['error'] = 'Invalid file type. Only JPG, PNG and GIF are allowed.';
                    } else {
                        $upload_dir = '../uploads/menu/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        $file_name = uniqid() . '_' . $_FILES['image']['name'];
                        $target_path = $upload_dir . $file_name;
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                            $image_path = 'uploads/menu/' . $file_name;
                        } else {
                            $_SESSION['error'] = 'Failed to upload image';
                        }
                    }
                }
                
                if (empty($_SESSION['error'])) {
                    if ($_POST['action'] === 'add') {
                        $stmt = $pdo->prepare("
                            INSERT INTO menu_items 
                            (restaurant_id, name, description, price, category, is_veg, image_path) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                        ");
                        
                        try {
                            $stmt->execute([
                                $restaurant_id,
                                $name,
                                $description,
                                $price,
                                $category,
                                $is_veg,
                                $image_path
                            ]);
                            $_SESSION['success'] = 'Menu item added successfully!';
                        } catch (PDOException $e) {
                            $_SESSION['error'] = 'Failed to add menu item. Please try again.';
                        }
                    } else {
                        // Edit existing item
                        $stmt = $pdo->prepare("
                            UPDATE menu_items 
                            SET name = ?, description = ?, price = ?, category = ?, is_veg = ?
                            " . ($image_path ? ", image_path = ?" : "") . "
                            WHERE id = ? AND restaurant_id = ?
                        ");
                        
                        $params = [
                            $name,
                            $description,
                            $price,
                            $category,
                            $is_veg
                        ];
                        
                        if ($image_path) {
                            $params[] = $image_path;
                        }
                        
                        $params[] = $item_id;
                        $params[] = $restaurant_id;
                        
                        try {
                            $stmt->execute($params);
                            $_SESSION['success'] = 'Menu item updated successfully!';
                        } catch (PDOException $e) {
                            $_SESSION['error'] = 'Failed to update menu item. Please try again.';
                        }
                    }
                }
            }
        } elseif ($_POST['action'] === 'delete' && isset($_POST['item_id'])) {
            $item_id = $_POST['item_id'];
            
            // Delete menu item
            $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ? AND restaurant_id = ?");
            try {
                $stmt->execute([$item_id, $restaurant_id]);
                $_SESSION['success'] = 'Menu item deleted successfully!';
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Failed to delete menu item. Please try again.';
            }
        }
    }
    
    // Redirect to prevent form resubmission
    header("Location: add-menu.php?res_id=" . $restaurant_id);
    exit();
}

// Get flash messages
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

// Clear flash messages
unset($_SESSION['error'], $_SESSION['success']);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title">Add Menu Item</h3>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data" id="menuForm">
                        <input type="hidden" name="action" value="add" id="formAction">
                        <input type="hidden" name="item_id" id="itemId">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Item Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" placeholder="e.g., Starters, Main Course, Desserts">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_veg" name="is_veg" checked>
                                <label class="form-check-label" for="is_veg">
                                    Vegetarian
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Item Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Add Item</button>
                            <button type="button" class="btn btn-outline-secondary" id="resetBtn" style="display: none;">Cancel Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <h2 class="mb-4">Menu Items</h2>
            
            <?php if (empty($menu_items)): ?>
                <div class="alert alert-info">No menu items added yet.</div>
            <?php else: ?>
                <?php
                $categories = [];
                foreach ($menu_items as $item) {
                    $cat = $item['category'] ?: 'Uncategorized';
                    if (!isset($categories[$cat])) {
                        $categories[$cat] = [];
                    }
                    $categories[$cat][] = $item;
                }
                ?>
                
                <?php foreach ($categories as $category => $items): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="m-0"><?php echo htmlspecialchars($category); ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($item['image_path']): ?>
                                                        <img src="<?php echo htmlspecialchars('../' . $item['image_path']); ?>" 
                                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                             class="img-thumbnail me-2" 
                                                             style="max-height: 50px;">
                                                    <?php endif; ?>
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($item['description']); ?></td>
                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                <td>
                                                    <?php if ($item['is_veg']): ?>
                                                        <span class="badge bg-success">Veg</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Non-Veg</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary edit-item"
                                                            data-id="<?php echo $item['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                                            data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                            data-price="<?php echo $item['price']; ?>"
                                                            data-category="<?php echo htmlspecialchars($item['category']); ?>"
                                                            data-is-veg="<?php echo $item['is_veg']; ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form method="POST" action="" class="d-inline">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('menuForm');
    const formAction = document.getElementById('formAction');
    const itemId = document.getElementById('itemId');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    
    // Edit item buttons
    document.querySelectorAll('.edit-item').forEach(button => {
        button.addEventListener('click', function() {
            const data = this.dataset;
            
            // Fill form with item data
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('price').value = data.price;
            document.getElementById('category').value = data.category;
            document.getElementById('is_veg').checked = data.isVeg === '1';
            
            // Change form to edit mode
            formAction.value = 'edit';
            itemId.value = data.id;
            submitBtn.textContent = 'Update Item';
            resetBtn.style.display = 'block';
            
            // Scroll to form
            form.scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // Reset form button
    resetBtn.addEventListener('click', function() {
        form.reset();
        formAction.value = 'add';
        itemId.value = '';
        submitBtn.textContent = 'Add Item';
        resetBtn.style.display = 'none';
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>

<?php
// Flush the output buffer
ob_end_flush();
?> 