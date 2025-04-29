<?php
// Start output buffering if not already started
if (ob_get_level() === 0) {
    ob_start();
}

require_once 'session.php';
require_once 'db.php'; // Add database connection
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/localcarving";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalCarving - Discover Local Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #FF6B6B;
            --secondary-color: #4ECDC4;
            --dark-color: #2C3E50;
            --light-color: #F7F9FC;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 76px; /* Height of fixed navbar */
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: bold;
            font-size: 1.5rem;
            padding: 0.5rem 1rem;
        }
        
        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: background-color 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(44, 62, 80, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <?php if (isLoggedIn() && isOwner()): ?>
                <a class="navbar-brand" href="<?php echo $base_url; ?>/index.php">LocalCarving</a>
            <?php else: ?>
                <a class="navbar-brand" href="<?php echo $base_url; ?>/index.php">LocalCarving</a>
            <?php endif; ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (!isLoggedIn() || !isOwner()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url; ?>/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url; ?>/restaurants.php">Restaurants</a>
                        </li> 
                    <?php endif; ?>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isOwner()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $base_url; ?>/index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $base_url; ?>/owner/dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $base_url; ?>/owner/manage-orders.php">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $base_url; ?>/owner/reviews.php">Reviews</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $base_url; ?>/user/dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $base_url; ?>/user/orders.php">Orders</a>
                            </li>
 
                        <?php endif; ?>
 
                    <?php endif; ?>
                    <li><a href="<?php echo $base_url; ?>/about.php" class="nav-link">About</a></li>
                    <li><a href="<?php echo $base_url; ?>/AI_MODEL_CHATBOT/chatbot.php" class="nav-link">ChatBot</a></li>

                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if (isOwner()): ?>
                                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>/owner/dashboard.php">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>/owner/manage-orders.php">Orders</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>/owner/reviews.php">Reviews</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                 <?php else: ?>
                                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>/user/dashboard.php">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>/user/orders.php">My Orders</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>/user/favorites.php">Favorites</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>/user/reviews.php">My Reviews</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                 <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo $base_url; ?>/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url; ?>/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url; ?>/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="flex-grow-1">
</body>
</html> 