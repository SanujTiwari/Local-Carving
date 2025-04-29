<?php
// Test script to verify API key and connection to Gemini API

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

// HTML header
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalCarving AI Chatbot - API Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .response {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            white-space: pre-wrap;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>LocalCarving AI Chatbot - API Test</h1>';

// Check if API key exists
if (empty($api_key)) {
    echo '<div class="error">Error: API key not found in .env file</div>';
    echo '<div class="info">Please create a .env file with your Google API key:</div>';
    echo '<div class="info">GOOGLE_API_KEY=your_api_key_here</div>';
    echo '<p><a href="chatbot.php" class="button">Go to Chatbot</a></p>';
    echo '</body></html>';
    exit;
}

// Check if API key is valid (not the default placeholder)
if ($api_key === 'your_api_key_here') {
    echo '<div class="error">Error: You need to replace the placeholder API key with your actual Google API key</div>';
    echo '<div class="info">Please edit the .env file and replace "your_api_key_here" with your actual API key</div>';
    echo '<p><a href="chatbot.php" class="button">Go to Chatbot</a></p>';
    echo '</body></html>';
    exit;
}

// Test API connection
echo '<div class="info">Testing connection to Gemini API...</div>';

// Simple test prompt
$prompt = "Hello, this is a test message. Please respond with a short greeting.";

// Call Gemini API
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

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Check for curl errors
if (!empty($curl_error)) {
    echo '<div class="error">Error: ' . htmlspecialchars($curl_error) . '</div>';
    echo '<div class="info">This may be due to network issues or PHP curl extension not being enabled.</div>';
    echo '<p><a href="chatbot.php" class="button">Go to Chatbot</a></p>';
    echo '</body></html>';
    exit;
}

// Check HTTP response code
if ($http_code !== 200) {
    echo '<div class="error">Error: HTTP ' . $http_code . ' - Failed to connect to Gemini API</div>';
    echo '<div class="info">This may be due to an invalid API key or API access issues.</div>';
    echo '<div class="info">Response: ' . htmlspecialchars($response) . '</div>';
    echo '<p><a href="chatbot.php" class="button">Go to Chatbot</a></p>';
    echo '</body></html>';
    exit;
}

// Parse response
$result = json_decode($response, true);
$bot_response = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response text found';

// Display success message
echo '<div class="success">Success! API key is valid and working correctly.</div>';
echo '<div class="info">Your API key is properly configured and can communicate with the Gemini API.</div>';
echo '<h2>Test Response:</h2>';
echo '<div class="response">' . htmlspecialchars($bot_response) . '</div>';
echo '<p><a href="chatbot.php" class="button">Go to Chatbot</a></p>';

// Close HTML
echo '</body></html>';
?> 