<?php
// Start output buffering if not already started
if (ob_get_level() === 0) {
    ob_start();
}

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

function isOwner() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'owner';
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        $current_page = $_SERVER['REQUEST_URI'];
        header('Location: login.php?redirect=' . urlencode($current_page));
        exit();
    }
}

function requireUser() {
    if (!isUser()) {
        if (!isLoggedIn()) {
            $current_page = $_SERVER['REQUEST_URI'];
            header('Location: login.php?redirect=' . urlencode($current_page));
        } else {
            header('Location: index.php');
        }
        exit();
    }
}

function requireOwner() {
    if (!isOwner()) {
        if (!isLoggedIn()) {
            $current_page = $_SERVER['REQUEST_URI'];
            header('Location: login.php?redirect=' . urlencode($current_page));
        } else {
            header('Location: index.php');
        }
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        if (!isLoggedIn()) {
            $current_page = $_SERVER['REQUEST_URI'];
            header('Location: login.php?redirect=' . urlencode($current_page));
        } else {
            header('Location: index.php');
        }
        exit();
    }
}

function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        if (isOwner()) {
            header('Location: owner/dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit();
    }
}

// Function to get the current user's ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Function to get the current user's role
function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

// Function to get the current user's username
function getCurrentUsername() {
    return $_SESSION['username'] ?? null;
}
?> 