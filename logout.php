<?php
require_once 'includes/session.php';

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit();
?> 