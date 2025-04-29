<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Restrict access to owners only
requireOwner();

$error = '';
$success = '';
$restaurant = null;

// Get restaurant ID from URL
$restaurant_id = $_GET['id'] ?? null;

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $category = $_POST['category'] ?? '';
    $opening_time = $_POST['opening_time'] ?? '';
    $closing_time = $_POST['closing_time'] ?? '';
    
    // Validation
    if (empty($name) || empty($address) || empty($city) || empty($phone) || empty($category)) {
        $error = 'Please fill in all required fields';
    } else {
        $logo_path = $restaurant['logo_path'];
        
        // Handle file upload if new logo is provided
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['logo']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                $error = 'Invalid file type. Only JPG, PNG and GIF are allowed.';
            } else {
                $upload_dir = '../uploads/logos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_name = uniqid() . '_' . $_FILES['logo']['name'];
                $target_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                    // Delete old logo if exists
                    if ($logo_path && file_exists('../' . $logo_path)) {
                        unlink('../' . $logo_path);
                    }
                    $logo_path = 'uploads/logos/' . $file_name;
                } else {
                    $error = 'Failed to upload logo';
                }
            }
        }
        
        if (empty($error)) {
            $stmt = $pdo->prepare("
                UPDATE restaurants 
                SET name = ?, description = ?, address = ?, city = ?, phone = ?, 
                    email = ?, category = ?, opening_time = ?, closing_time = ?, logo_path = ?
                WHERE id = ? AND owner_id = ?
            ");
            
            try {
                $stmt->execute([
                    $name,
                    $description,
                    $address,
                    $city,
                    $phone,
                    $email,
                    $category,
                    $opening_time,
                    $closing_time,
                    $logo_path,
                    $restaurant_id,
                    $_SESSION['user_id']
                ]);
                $success = 'Restaurant updated successfully!';
                
                // Update local restaurant data
                $restaurant = array_merge($restaurant, [
                    'name' => $name,
                    'description' => $description,
                    'address' => $address,
                    'city' => $city,
                    'phone' => $phone,
                    'email' => $email,
                    'category' => $category,
                    'opening_time' => $opening_time,
                    'closing_time' => $closing_time,
                    'logo_path' => $logo_path
                ]);
            } catch (PDOException $e) {
                $error = 'Failed to update restaurant. Please try again.';
            }
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Edit Restaurant</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Restaurant Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Street Food" <?php echo $restaurant['category'] === 'Street Food' ? 'selected' : ''; ?>>Street Food</option>
                                    <option value="Café" <?php echo $restaurant['category'] === 'Café' ? 'selected' : ''; ?>>Café</option>
                                    <option value="Restaurant" <?php echo $restaurant['category'] === 'Restaurant' ? 'selected' : ''; ?>>Restaurant</option>
                                    <option value="Fast Food" <?php echo $restaurant['category'] === 'Fast Food' ? 'selected' : ''; ?>>Fast Food</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($restaurant['description']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($restaurant['address']); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($restaurant['city']); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($restaurant['phone']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($restaurant['email']); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="opening_time" class="form-label">Opening Time</label>
                                <input type="time" class="form-control" id="opening_time" name="opening_time" value="<?php echo $restaurant['opening_time']; ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="closing_time" class="form-label">Closing Time</label>
                                <input type="time" class="form-control" id="closing_time" name="closing_time" value="<?php echo $restaurant['closing_time']; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="logo" class="form-label">Restaurant Logo</label>
                            <?php if ($restaurant['logo_path']): ?>
                                <div class="mb-2">
                                    <img src="<?php echo htmlspecialchars('../' . $restaurant['logo_path']); ?>" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <div class="form-text">Upload a new logo image (JPG, PNG, or GIF) or leave empty to keep the current one</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Restaurant</button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 