<?php
// Start output buffering
ob_start();

require_once 'includes/session.php';
require_once 'includes/db.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    
    if (empty($username) || empty($password) || empty($role)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
        $stmt->execute([$username, $role]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Update last login time
            $stmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Log the activity
            $stmt = $pdo->prepare("
                INSERT INTO activity_log (user_id, action, description, ip_address)
                VALUES (?, 'login', ?, ?)
            ");
            $description = "User logged in";
            $ip = $_SERVER['REMOTE_ADDR'];
            $stmt->execute([$user['id'], $description, $ip]);
            
            // Handle redirect
            if (!empty($redirect)) {
                header('Location: ' . $redirect);
            } else if ($user['role'] === 'owner') {
                header('Location: owner/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}

// Include header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Login to LocalCarving</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <?php if (!empty($redirect)): ?>
                            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Login As</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="user">Normal User</option>
                                <option value="owner">Restaurant Owner</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require_once 'includes/footer.php';

// Flush the output buffer
ob_end_flush();
?> 