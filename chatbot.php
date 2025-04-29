<?php
session_start();

// Initialize chat history if it doesn't exist
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
    // Add initial greeting message
    $_SESSION['chat_history'][] = [
        'role' => 'assistant',
        'message' => "👋 **Welcome to LocalCarving!**\n\nI'm your AI assistant, ready to help you with:\n\n* 🍽️ Restaurant discovery\n* 🚚 Food delivery\n* 📱 App features\n* ❓ General questions\n\nHow can I assist you today?"
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $user_message = trim($_POST['message']);
    
    if (!empty($user_message)) {
        // Add user message to chat history
        $_SESSION['chat_history'][] = [
            'role' => 'user',
            'message' => $user_message
        ];

        // Call the API
        $ch = curl_init('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/chat_api.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $user_message]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $result = json_decode($response, true);
            if (isset($result['response'])) {
                // Add AI response to chat history
                $_SESSION['chat_history'][] = [
                    'role' => 'assistant',
                    'message' => $result['response']
                ];
            }
        }
    }
}

// Clear chat history if requested
if (isset($_GET['clear'])) {
    $_SESSION['chat_history'] = [];
    // Add initial greeting message after clearing
    $_SESSION['chat_history'][] = [
        'role' => 'assistant',
        'message' => "👋 **Welcome to LocalCarving!**\n\nI'm your AI assistant, ready to help you with:\n\n* 🍽️ Restaurant discovery\n* 🚚 Food delivery\n* 📱 App features\n* ❓ General questions\n\nHow can I assist you today?"
    ];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Function to format message with markdown-style text
function formatMessage($message) {
    // Convert markdown-style formatting to HTML
    $message = htmlspecialchars($message);
    
    // Bold text
    $message = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $message);
    
    // Italic text
    $message = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $message);
    
    // Bullet points
    $message = preg_replace('/\n\* (.*?)(?=\n|$)/', '<br>• $1', $message);
    
    // Line breaks
    $message = nl2br($message);
    
    return $message;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalCarving AI Chatbot</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow-x: hidden;
            width: 100vw;
            height: 100vh;
        }
        
        /* Enhanced background elements */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: 
                radial-gradient(circle at 20% 20%, rgba(52, 152, 219, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(46, 204, 113, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(44, 62, 80, 0.05) 0%, transparent 70%);
            animation: rotate 120s linear infinite;
            z-index: -2;
            pointer-events: none;
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: 
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><circle cx="50" cy="50" r="1" fill="%232c3e50" opacity="0.1"/></svg>'),
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60"><rect width="60" height="60" fill="none"/><circle cx="30" cy="30" r="1.5" fill="%233498db" opacity="0.1"/></svg>');
            background-size: 30px 30px, 60px 60px;
            background-position: 0 0, 15px 15px;
            z-index: -1;
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Floating elements */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
        }
        
        .floating-element {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 15s infinite ease-in-out;
            pointer-events: none;
        }
        
        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #f1c40f, #f39c12);
            bottom: 15%;
            left: 20%;
            animation-delay: 4s;
        }
        
        .floating-element:nth-child(4) {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            bottom: 25%;
            right: 10%;
            animation-delay: 6s;
        }
        
        .floating-element:nth-child(5) {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            top: 40%;
            left: 30%;
            animation-delay: 8s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(5deg); }
            50% { transform: translateY(0) rotate(0deg); }
            75% { transform: translateY(20px) rotate(-5deg); }
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .page-container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
        }
        
        .header-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .home-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .home-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            background-color: #f8f9fa;
        }
        
        .home-link svg {
            margin-right: 8px;
            width: 20px;
            height: 20px;
        }
        
        .chat-container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 80vh;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .chat-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .chat-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 500;
        }
        
        .chat-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2ecc71, #f1c40f, #e74c3c);
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background-color: #f9f9f9;
            scroll-behavior: smooth;
        }
        
        .message {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.3s ease forwards;
        }
        
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message.user {
            align-items: flex-end;
        }
        
        .message.assistant {
            align-items: flex-start;
        }
        
        .message-content {
            max-width: 70%;
            padding: 14px 18px;
            border-radius: 18px;
            margin: 4px 0;
            line-height: 1.5;
            font-size: 0.95rem;
        }
        
        .user .message-content {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 2px 5px rgba(52, 152, 219, 0.2);
        }
        
        .assistant .message-content {
            background-color: white;
            color: #2c3e50;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #eaeaea;
        }
        
        .assistant .message-content strong {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .assistant .message-content em {
            color: #7f8c8d;
            font-style: italic;
        }
        
        .chat-input {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            background-color: white;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 14px 18px;
            border: 1px solid #e0e0e0;
            border-radius: 24px;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }
        
        input[type="text"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        button {
            padding: 14px 24px;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border: none;
            border-radius: 24px;
            cursor: pointer;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(44, 62, 80, 0.2);
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(44, 62, 80, 0.3);
        }
        
        .clear-chat {
            text-align: center;
            margin-top: 20px;
        }
        
        .clear-chat a {
            color: #7f8c8d;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .clear-chat a:hover {
            color: #e74c3c;
        }
        
        .typing-indicator {
            display: none;
            padding: 14px 18px;
            background-color: white;
            border-radius: 18px;
            margin: 4px 0;
            color: #7f8c8d;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.3s ease forwards;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #eaeaea;
        }
        
        .typing-indicator::after {
            content: '...';
            animation: typing 1.5s infinite;
        }
        
        @keyframes typing {
            0%, 100% { content: '.'; }
            33% { content: '..'; }
            66% { content: '...'; }
        }
        
        /* Scrollbar styling */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .chat-messages::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .chat-container {
                height: 90vh;
                border-radius: 0;
            }
            
            .message-content {
                max-width: 85%;
            }
            
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .home-link {
                align-self: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Floating background elements -->
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    
    <div class="page-container">
        <div class="header-container">
            <a href="../index.php" class="home-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Back to Home
            </a>
        </div>
        
        <div class="chat-container">
            <div class="chat-header">
                <h1>LocalCarving AI Assistant</h1>
            </div>
            <div class="chat-messages" id="chatMessages">
                <?php foreach ($_SESSION['chat_history'] as $message): ?>
                    <div class="message <?php echo $message['role']; ?>">
                        <div class="message-content">
                            <?php echo formatMessage($message['message']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="typing-indicator" id="typingIndicator">AI is typing</div>
            </div>
            <form method="POST" class="chat-input" id="chatForm">
                <input type="text" name="message" placeholder="Type your message here..." required autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </div>
        <div class="clear-chat">
            <a href="?clear=1">Clear Chat History</a>
        </div>
    </div>

    <script>
        // Scroll to bottom of chat
        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Show typing indicator
        function showTypingIndicator() {
            document.getElementById('typingIndicator').style.display = 'block';
            scrollToBottom();
        }

        // Hide typing indicator
        function hideTypingIndicator() {
            document.getElementById('typingIndicator').style.display = 'none';
        }

        // Add message to chat
        function addMessage(message, role) {
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${role}`;
            
            // Format the message content
            let formattedMessage = message
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\n\* (.*?)(?=\n|$)/g, '<br>• $1')
                .replace(/\n/g, '<br>');
            
            messageDiv.innerHTML = `<div class="message-content">${formattedMessage}</div>`;
            chatMessages.insertBefore(messageDiv, document.getElementById('typingIndicator'));
            scrollToBottom();
        }

        // Handle form submission
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = this.querySelector('input[name="message"]');
            const message = input.value.trim();
            
            if (message) {
                // Add user message to chat
                addMessage(message, 'user');
                input.value = '';
                showTypingIndicator();
                
                fetch('chat_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    hideTypingIndicator();
                    if (data.response) {
                        // Add AI response to chat
                        addMessage(data.response, 'assistant');
                    }
                })
                .catch(error => {
                    hideTypingIndicator();
                    console.error('Error:', error);
                    addMessage('Sorry, there was an error processing your request. Please try again.', 'assistant');
                });
            }
        });

        // Scroll to bottom on page load
        scrollToBottom();
    </script>
</body>
</html> 