<?php
// Set content type to JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load environment variables
function loadEnv($path) {
    if (file_exists($path)) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }
}

// Load environment variables
loadEnv(__DIR__ . '/.env');

// Get API key from environment
$api_key = $_ENV['GOOGLE_API_KEY'] ?? '';

// Check if API key is configured
if (empty($api_key)) {
    echo json_encode(['error' => 'API key not configured. Please set GOOGLE_API_KEY in your .env file.']);
    exit;
}

 //Define the context for the chatbot
 //$WEBSITE_CONTEXT = "You are a helpful assistant for LocalCarving, a restaurant discovery and food delivery platform. 
 //You can help users find restaurants, explore menus, place orders, and answer questions about food delivery services.
 //You have access to information about various cuisines, dietary restrictions, and restaurant ratings.
 //Your goal is to provide accurate, helpful, and friendly responses to user queries.";


// Define the context for the chatbot
$WEBSITE_CONTEXT = " give answer ";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $user_message = $input['message'] ?? '';

    if (empty($user_message)) {
        echo json_encode(['error' => 'No message provided']);
        exit;
    }

    // Prepare the prompt for the Gemini API
    $prompt = $WEBSITE_CONTEXT . "\n\nUser: " . $user_message . "\n\nAssistant:";

    // Call the Gemini API
    $url = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=' . $api_key;
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 1024,
        ]
    ];

    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Execute cURL request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Check for curl errors
    if (!empty($curl_error)) {
        echo json_encode(['error' => 'cURL Error: ' . $curl_error]);
        exit;
    }

    // Check HTTP response code
    if ($http_code !== 200) {
        echo json_encode([
            'error' => 'HTTP ' . $http_code . ' - Failed to connect to Gemini API',
            'details' => $response
        ]);
        exit;
    }

    // Parse the response
    $result = json_decode($response, true);
    
    // Extract the generated content
    $generated_content = '';
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $generated_content = $result['candidates'][0]['content']['parts'][0]['text'];
    } else {
        $generated_content = 'Sorry, I could not generate a response. Please try again.';
    }

    // Return the response
    echo json_encode(['response' => $generated_content]);
} else {
    // Handle non-POST requests
    echo json_encode(['error' => 'Only POST requests are supported']);
}
?> 