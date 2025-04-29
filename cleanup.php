<?php
// This script helps clean up unnecessary files for the PHP implementation

$files_to_remove = [
    'requirements.txt',
    'chatbot.html',
    'chatbot.py'
];

$removed = [];
$not_found = [];

foreach ($files_to_remove as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            $removed[] = $file;
        } else {
            $not_found[] = $file . " (could not delete)";
        }
    } else {
        $not_found[] = $file . " (not found)";
    }
}

echo "<h1>LocalCarving AI Chatbot Cleanup</h1>";

if (count($removed) > 0) {
    echo "<h2>Successfully removed:</h2>";
    echo "<ul>";
    foreach ($removed as $file) {
        echo "<li>$file</li>";
    }
    echo "</ul>";
}

if (count($not_found) > 0) {
    echo "<h2>Not found or could not be removed:</h2>";
    echo "<ul>";
    foreach ($not_found as $file) {
        echo "<li>$file</li>";
    }
    echo "</ul>";
}

echo "<p>Cleanup complete. You can now delete this file (cleanup.php) as well.</p>";
echo "<p><a href='chatbot.php'>Go to Chatbot</a></p>";
?> 