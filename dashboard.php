<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/session.php';
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/localcarving";

// Restrict access to owners only
requireOwner();

// Get owner's restaurants
$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE owner_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Restaurants</h2>
        <a href="add-restaurant.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Restaurant
        </a>
    </div>
    
    <?php if (empty($restaurants)): ?>
        <div class="alert alert-info">
            You haven't added any restaurants yet. <a href="add-restaurant.php">Add your first restaurant</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($restaurants as $restaurant): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($restaurant['logo_path']): ?>
                    <img src="<?php echo $base_url; ?>/<?php echo htmlspecialchars($restaurant['logo_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
                    <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($restaurant['city']); ?>
                        </p>
                        <p class="card-text"><?php echo htmlspecialchars($restaurant['description']); ?></p>
                        
                        <div class="d-flex justify-content-between">
                            <a href="edit-restaurant.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="add-menu.php?res_id=<?php echo $restaurant['id']; ?>" class="btn btn-outline-success">
                                <i class="bi bi-list"></i> Manage Menu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?> 