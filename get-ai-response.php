<?php
// Start output buffering
ob_start();

require_once 'includes/session.php';
require_once 'includes/ai-chat.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

// Get the JSON data from the request
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Check if we have the required data
if (!isset($data['question']) || !isset($data['role'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Missing required data']);
    exit();
}

// Get the question and role
$question = $data['question'];
$role = $data['role'];

// Get AI response
$response = getAiResponse($question, $role);

// Return the response
header('Content-Type: application/json');
echo json_encode(['response' => $response]);

// Flush the output buffer
ob_end_flush();
?> 