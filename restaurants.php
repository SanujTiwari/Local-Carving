<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Get search parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$city = $_GET['city'] ?? '';
$is_open = isset($_GET['is_open']) ? true : false;

// Build query
$query = "SELECT * FROM restaurants WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (name LIKE ? OR city LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
}

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
}

if ($city) {
    $query .= " AND city = ?";
    $params[] = $city;
}

if ($is_open) {
    $current_time = date('H:i:s');
    $query .= " AND TIME(?) BETWEEN opening_time AND closing_time";
    $params[] = $current_time;
}

$query .= " ORDER BY name";

// Execute query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get unique categories and cities for filters
$categories = $pdo->query("SELECT DISTINCT category FROM restaurants ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
$cities = $pdo->query("SELECT DISTINCT city FROM restaurants ORDER BY city")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container my-5">
    <div class="row">
        <!-- Filters -->
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filters</h5>
                    <form action="" method="GET">
                        <?php if ($search): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                                            <?php echo $category === $cat ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <select name="city" class="form-select" onchange="this.form.submit()">
                                <option value="">All Cities</option>
                                <?php foreach ($cities as $c): ?>
                                    <option value="<?php echo htmlspecialchars($c); ?>" 
                                            <?php echo $city === $c ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_open" name="is_open" 
                                   <?php echo $is_open ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <label class="form-check-label" for="is_open">Open Now</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Restaurant List -->
        <div class="col-md-9">
            <!-- Search Bar -->
            <form action="" method="GET" class="mb-4">
                <?php if ($category): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <?php endif; ?>
                <?php if ($city): ?>
                    <input type="hidden" name="city" value="<?php echo htmlspecialchars($city); ?>">
                <?php endif; ?>
                <?php if ($is_open): ?>
                    <input type="hidden" name="is_open" value="1">
                <?php endif; ?>
                
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search restaurants by name, city or description..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
            
            <?php if (empty($restaurants)): ?>
                <div class="alert alert-info">
                    No restaurants found matching your criteria.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($restaurants as $restaurant): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <?php if ($restaurant['logo_path']): ?>
                                    <img src="<?php echo htmlspecialchars($restaurant['logo_path']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="bi bi-shop text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                                    
                                    <p class="card-text text-muted mb-2">
                                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($restaurant['city']); ?>
                                        <span class="ms-3">
                                            <i class="bi bi-tag"></i> <?php echo htmlspecialchars($restaurant['category']); ?>
                                        </span>
                                    </p>
                                    
                                    <?php
                                    $current_time = strtotime(date('H:i:s'));
                                    $opening_time = strtotime($restaurant['opening_time']);
                                    $closing_time = strtotime($restaurant['closing_time']);
                                    $is_restaurant_open = $current_time >= $opening_time && $current_time <= $closing_time;
                                    ?>
                                    
                                    <p class="card-text mb-3">
                                        <?php if ($is_restaurant_open): ?>
                                            <span class="badge bg-success">Open Now</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Closed</span>
                                        <?php endif; ?>
                                        
                                        <small class="text-muted ms-2">
                                            <?php echo date('g:i A', strtotime($restaurant['opening_time'])); ?> - 
                                            <?php echo date('g:i A', strtotime($restaurant['closing_time'])); ?>
                                        </small>
                                    </p>
                                    
                                    <p class="card-text">
                                        <?php 
                                        $description = $restaurant['description'];
                                        echo strlen($description) > 100 ? 
                                             htmlspecialchars(substr($description, 0, 100)) . '...' : 
                                             htmlspecialchars($description);
                                        ?>
                                    </p>
                                    
                                    <a href="restaurant.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-primary">
                                        View Menu
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 