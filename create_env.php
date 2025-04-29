<?php
// Script to help create the .env file

$env_file = __DIR__ . '/.env';
$env_content = "# Google Gemini API Key\n# Get your API key from: https://makersuite.google.com/app/apikey\nGOOGLE_API_KEY=your_api_key_here\n";

// Check if .env file already exists
if (file_exists($env_file)) {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LocalCarving AI Chatbot - .env File</title>
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
            .info {
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                margin: 10px 0;
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
        <h1>LocalCarving AI Chatbot - .env File</h1>
        <div class="info">The .env file already exists. If you need to update your API key, please edit the file manually.</div>
        <p><a href="test_api.php" class="button">Test API Connection</a></p>
        <p><a href="chatbot.php" class="button">Go to Chatbot</a></p>
    </body>
    </html>';
    exit;
}

// Create .env file
if (file_put_contents($env_file, $env_content)) {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LocalCarving AI Chatbot - .env File Created</title>
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
            .info {
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                margin: 10px 0;
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
        <h1>LocalCarving AI Chatbot - .env File Created</h1>
        <div class="success">Success! The .env file has been created.</div>
        <div class="info">Please edit the .env file and replace "your_api_key_here" with your actual Google API key.</div>
        <div class="info">To get an API key, go to: <a href="https://makersuite.google.com/app/apikey" target="_blank">https://makersuite.google.com/app/apikey</a></div>
        <p><a href="test_api.php" class="button">Test API Connection</a></p>
        <p><a href="chatbot.php" class="button">Go to Chatbot</a></p>
    </body>
    </html>';
} else {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LocalCarving AI Chatbot - Error</title>
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
        <h1>LocalCarving AI Chatbot - Error</h1>
        <div class="error">Error: Could not create the .env file.</div>
        <div class="info">Please create the .env file manually with the following content:</div>
        <div class="info">
            <pre># Google Gemini API Key
# Get your API key from: https://makersuite.google.com/app/apikey
GOOGLE_API_KEY=your_api_key_here</pre>
        </div>
        <p><a href="chatbot.php" class="button">Go to Chatbot</a></p>
    </body>
    </html>';
}
?> 