<?php
// Check if .env file exists
$env_file = __DIR__ . '/.env';
$env_exists = file_exists($env_file);

// Check if API key is configured
$api_key_configured = false;
if ($env_exists) {
    $env_content = file_get_contents($env_file);
    $api_key_configured = strpos($env_content, 'GOOGLE_API_KEY=your_api_key_here') === false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalCarving AI Chatbot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
            background-color: #f5f5f5;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .status {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .status.success {
            background-color: #d4edda;
            color: #155724;
        }
        .status.warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .status.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .button.secondary {
            background-color: #6c757d;
        }
        .button.success {
            background-color: #28a745;
        }
        .steps {
            margin-left: 20px;
        }
        .steps li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>LocalCarving AI Chatbot</h1>
    
    <div class="container">
        <h2>Setup Status</h2>
        
        <?php if (!$env_exists): ?>
            <div class="status error">
                <strong>Error:</strong> .env file not found. Please create it to configure your API key.
            </div>
            <p>Follow these steps to set up your chatbot:</p>
            <ol class="steps">
                <li>Click the button below to create the .env file</li>
                <li>Edit the .env file and replace "your_api_key_here" with your actual Google API key</li>
                <li>Test the API connection</li>
                <li>Start using the chatbot</li>
            </ol>
            <p><a href="create_env.php" class="button">Create .env File</a></p>
        <?php elseif (!$api_key_configured): ?>
            <div class="status warning">
                <strong>Warning:</strong> API key not configured. Please update your .env file with your actual API key.
            </div>
            <p>Follow these steps to complete setup:</p>
            <ol class="steps">
                <li>Edit the .env file and replace "your_api_key_here" with your actual Google API key</li>
                <li>Test the API connection</li>
                <li>Start using the chatbot</li>
            </ol>
            <p><a href="test_api.php" class="button">Test API Connection</a></p>
        <?php else: ?>
            <div class="status success">
                <strong>Success:</strong> API key is configured. You're ready to use the chatbot!
            </div>
            <p><a href="chatbot.php" class="button success">Open Chatbot</a></p>
        <?php endif; ?>
    </div>
    
    <div class="container">
        <h2>Available Tools</h2>
        <p><a href="chatbot.php" class="button">Chatbot Interface</a></p>
        <p><a href="test_api.php" class="button">Test API Connection</a></p>
        <?php if (!$env_exists): ?>
            <p><a href="create_env.php" class="button">Create .env File</a></p>
        <?php endif; ?>
        <p><a href="cleanup.php" class="button secondary">Clean Up Files</a></p>
    </div>
    
    <div class="container">
        <h2>Documentation</h2>
        <p><a href="README.md" class="button secondary">View README</a></p>
        <p><a href="INSTALLATION.md" class="button secondary">View Installation Guide</a></p>
    </div>
</body>
</html> 