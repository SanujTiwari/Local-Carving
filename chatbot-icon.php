<?php
// Only show chatbot icon for logged-in users
if (isLoggedIn()):
?>
<div class="chatbot-icon">
    <a href="#" id="open-chatbot" class="text-decoration-none">
        <i class="bi bi-chat-dots-fill"></i>
    </a>
</div>

<!-- Chatbot Popup -->
<div id="chatbot-popup" class="chatbot-popup">
    <div class="chatbot-header">
        <div class="d-flex align-items-center">
            <div class="chatbot-avatar me-2">
                <i class="bi bi-robot"></i>
            </div>
            <h5 class="mb-0">LocalCarving Assistant</h5>
        </div>
        <div class="d-flex align-items-center">
            <div class="mode-toggle me-2" id="mode-toggle" title="Toggle between Chatbot and AI mode">
                <i class="bi bi-robot"></i>
            </div>
            <button type="button" class="btn-close" id="close-chatbot"></button>
        </div>
    </div>
    <div class="chatbot-body" id="chat-messages">
        <div class="chat-message bot">
            <div class="message-content">
                <div class="message-text">Hello! I'm your LocalCarving assistant. How can I help you today?</div>
                <div class="message-time">Just now</div>
            </div>
        </div>
    </div>
    <div class="chatbot-footer">
        <form id="chat-form" class="d-flex">
            <input type="text" id="user-input" class="form-control" placeholder="Type your message..." required>
            <button type="submit" class="btn btn-primary ms-2">
                <i class="bi bi-send"></i>
            </button>
        </form>
    </div>
</div>

<style>
.chatbot-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.chatbot-icon a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.chatbot-icon a:hover {
    background-color: #0056b3;
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}

.chatbot-icon a.active {
    background-color: #0056b3;
    transform: scale(0.95);
}

.chatbot-icon i {
    font-size: 28px;
}

.chatbot-popup {
    display: none;
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 380px;
    height: 550px;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    z-index: 1000;
    flex-direction: column;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    overflow: hidden;
}

.chatbot-popup.show {
    opacity: 1;
    transform: translateY(0);
}

.chatbot-header {
    padding: 16px;
    background-color: #007bff;
    color: white;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-avatar {
    width: 36px;
    height: 36px;
    background-color: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chatbot-avatar i {
    font-size: 20px;
}

.mode-toggle {
    width: 32px;
    height: 32px;
    background-color: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mode-toggle:hover {
    background-color: rgba(255,255,255,0.3);
}

.mode-toggle.active {
    background-color: rgba(255,255,255,0.4);
}

.mode-toggle i {
    font-size: 16px;
}

.chatbot-header .btn-close {
    color: white;
    opacity: 0.8;
    transition: opacity 0.3s ease;
    filter: brightness(0) invert(1);
}

.chatbot-header .btn-close:hover {
    opacity: 1;
}

.chatbot-body {
    flex-grow: 1;
    padding: 16px;
    overflow-y: auto;
    background-color: #f8f9fa;
    scroll-behavior: smooth;
}

.chatbot-footer {
    padding: 16px;
    border-top: 1px solid #dee2e6;
    background-color: white;
}

.chat-message {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-message.user {
    align-items: flex-end;
}

.chat-message.bot {
    align-items: flex-start;
}

.message-content {
    max-width: 85%;
    padding: 12px 16px;
    border-radius: 18px;
    background-color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    position: relative;
}

.user .message-content {
    background-color: #007bff;
    color: white;
}

.message-time {
    font-size: 11px;
    color: #6c757d;
    margin-top: 4px;
    text-align: right;
}

.user .message-time {
    color: rgba(255,255,255,0.8);
}

.typing-indicator {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background-color: white;
    border-radius: 18px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    width: fit-content;
}

.typing-indicator span {
    height: 8px;
    width: 8px;
    background-color: #6c757d;
    border-radius: 50%;
    display: inline-block;
    margin: 0 2px;
    animation: typing 1s infinite ease-in-out;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.ai-option {
    background-color: #f0f7ff;
    border: 1px solid #cce5ff;
    border-radius: 18px;
    padding: 12px 16px;
    margin-top: 8px;
    animation: fadeIn 0.3s ease;
}

.ai-option-title {
    font-weight: 600;
    color: #0056b3;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.ai-option-title i {
    margin-right: 8px;
}

.ai-option-buttons {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.ai-option-button {
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ai-option-button.primary {
    background-color: #007bff;
    color: white;
}

.ai-option-button.secondary {
    background-color: #e9ecef;
    color: #495057;
}

.ai-option-button:hover {
    transform: translateY(-2px);
}

.ai-option-button i {
    margin-right: 6px;
}

.ai-response {
    background-color: #f0f7ff;
    border-left: 3px solid #007bff;
}

.ai-badge {
    display: inline-block;
    background-color: #007bff;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    margin-bottom: 6px;
}

.mode-indicator {
    display: inline-block;
    background-color: rgba(255,255,255,0.2);
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 8px;
}

#chat-form {
    display: flex;
    gap: 10px;
}

#user-input {
    flex-grow: 1;
    border-radius: 24px;
    padding: 10px 16px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
    font-size: 14px;
}

#user-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    outline: none;
}

#chat-form button {
    border-radius: 50%;
    width: 42px;
    height: 42px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background-color: #007bff;
    border: none;
}

#chat-form button:hover {
    transform: scale(1.1);
    background-color: #0056b3;
}

#chat-form button i {
    font-size: 18px;
}

@media (max-width: 576px) {
    .chatbot-popup {
        width: calc(100% - 32px);
        height: 70vh;
        bottom: 80px;
        right: 16px;
    }
    
    .chatbot-icon {
        bottom: 16px;
        right: 16px;
    }
    
    .chatbot-icon a {
        width: 50px;
        height: 50px;
    }
    
    .chatbot-icon i {
        font-size: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotIcon = document.getElementById('open-chatbot');
    const chatbotPopup = document.getElementById('chatbot-popup');
    const closeChatbot = document.getElementById('close-chatbot');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const chatMessages = document.getElementById('chat-messages');
    const modeToggle = document.getElementById('mode-toggle');
    
    let isChatOpen = false;
    let lastUserMessage = '';
    let isAiMode = false;

    // Toggle chatbot
    function toggleChatbot() {
        isChatOpen = !isChatOpen;
        chatbotIcon.classList.toggle('active');
        
        if (isChatOpen) {
            chatbotPopup.style.display = 'flex';
            // Trigger reflow to enable animation
            chatbotPopup.offsetHeight;
            chatbotPopup.classList.add('show');
            userInput.focus();
        } else {
            chatbotPopup.classList.remove('show');
            setTimeout(() => {
                chatbotPopup.style.display = 'none';
            }, 300);
        }
    }

    // Toggle AI mode
    function toggleAiMode() {
        isAiMode = !isAiMode;
        modeToggle.classList.toggle('active');
        
        // Update the mode indicator in the header
        const headerTitle = document.querySelector('.chatbot-header h5');
        
        if (isAiMode) {
            headerTitle.innerHTML = 'LocalCarving AI <span class="mode-indicator">AI Mode</span>';
            addMessage('AI mode activated. I will now use AI to answer your questions.', 'bot', true);
        } else {
            headerTitle.innerHTML = 'LocalCarving Assistant <span class="mode-indicator">Chatbot Mode</span>';
            addMessage('Chatbot mode activated. I will now use predefined answers for your questions.', 'bot');
        }
    }

    // Open/close chatbot
    chatbotIcon.addEventListener('click', function(e) {
        e.preventDefault();
        toggleChatbot();
    });

    // Close chatbot
    closeChatbot.addEventListener('click', function() {
        toggleChatbot();
    });
    
    // Toggle AI mode
    modeToggle.addEventListener('click', function() {
        toggleAiMode();
    });

    // Function to add messages to chat
    function addMessage(message, type, isAiResponse = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${type}`;
        
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        let messageHtml = '';
        
        if (type === 'bot' && isAiResponse) {
            messageHtml = `
                <div class="message-content ai-response">
                    <div class="ai-badge">AI Response</div>
                    <div class="message-text">${message}</div>
                    <div class="message-time">${timeString}</div>
                </div>
            `;
        } else {
            messageHtml = `
                <div class="message-content">
                    <div class="message-text">${message}</div>
                    <div class="message-time">${timeString}</div>
                </div>
            `;
        }
        
        messageDiv.innerHTML = messageHtml;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Function to show typing indicator
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message bot';
        typingDiv.innerHTML = `
            <div class="typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return typingDiv;
    }
    
    // Function to remove typing indicator
    function removeTypingIndicator(indicator) {
        if (indicator && indicator.parentNode) {
            indicator.parentNode.removeChild(indicator);
        }
    }
    
    // Function to show AI option
    function showAiOption() {
        const aiOptionDiv = document.createElement('div');
        aiOptionDiv.className = 'chat-message bot';
        aiOptionDiv.innerHTML = `
            <div class="ai-option">
                <div class="ai-option-title">
                    <i class="bi bi-robot"></i> AI Assistant Available
                </div>
                <div>I couldn't find a specific answer to your question. Would you like to use our AI assistant for a more detailed response?</div>
                <div class="ai-option-buttons">
                    <button class="ai-option-button primary" id="useAiBtn">
                        <i class="bi bi-robot"></i> Use AI
                    </button>
                    <button class="ai-option-button secondary" id="skipAiBtn">
                        <i class="bi bi-x-circle"></i> Skip
                    </button>
                </div>
            </div>
        `;
        chatMessages.appendChild(aiOptionDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Add event listeners to the buttons
        document.getElementById('useAiBtn').addEventListener('click', function() {
            // Remove the AI option
            aiOptionDiv.remove();
            
            // Show typing indicator
            const typingIndicator = showTypingIndicator();
            
            // Send message to AI endpoint
            fetch('<?php echo $base_url; ?>/get-ai-response.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    question: lastUserMessage,
                    role: '<?php echo $_SESSION['role'] ?? 'user'; ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remove typing indicator
                removeTypingIndicator(typingIndicator);
                
                if (data.error) {
                    addMessage('Sorry, I encountered an error with the AI service. Please try again later.', 'bot');
                } else {
                    // Add AI response
                    addMessage(data.response, 'bot', true);
                    
                    // Switch to AI mode for future questions
                    if (!isAiMode) {
                        isAiMode = true;
                        modeToggle.classList.add('active');
                        const headerTitle = document.querySelector('.chatbot-header h5');
                        headerTitle.innerHTML = 'LocalCarving AI <span class="mode-indicator">AI Mode</span>';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                removeTypingIndicator(typingIndicator);
                addMessage('Sorry, I encountered an error with the AI service. Please try again later.', 'bot');
            });
        });
        
        document.getElementById('skipAiBtn').addEventListener('click', function() {
            // Remove the AI option
            aiOptionDiv.remove();
            
            // Add a message suggesting to try a different question
            addMessage('No problem! Feel free to ask a different question or try rephrasing your current one.', 'bot');
        });
    }

    // Handle form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = userInput.value.trim();
        if (message) {
            // Add user message
            addMessage(message, 'user');
            userInput.value = '';
            lastUserMessage = message;
            
            // Show typing indicator
            const typingIndicator = showTypingIndicator();
            
            // If in AI mode, send directly to AI endpoint
            if (isAiMode) {
                fetch('<?php echo $base_url; ?>/get-ai-response.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        question: message,
                        role: '<?php echo $_SESSION['role'] ?? 'user'; ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Remove typing indicator
                    removeTypingIndicator(typingIndicator);
                    
                    if (data.error) {
                        addMessage('Sorry, I encountered an error with the AI service. Please try again later.', 'bot', true);
                    } else {
                        // Add AI response
                        addMessage(data.response, 'bot', true);
                        
                        // Add suggested questions if available
                        if (data.suggestions && data.suggestions.length > 0) {
                            addSuggestedQuestions(data.suggestions);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    removeTypingIndicator(typingIndicator);
                    addMessage('Sorry, I encountered an error with the AI service. Please try again later.', 'bot', true);
                });
            } else {
                // Send message to server and get response
                fetch('<?php echo $base_url; ?>/api/chatbot.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => {
                    if (!response.ok) {
                        // If response is not OK (404), show AI option
                        removeTypingIndicator(typingIndicator);
                        showAiOption();
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        // Remove typing indicator
                        removeTypingIndicator(typingIndicator);
                        
                        // Add bot response
                        addMessage(data.response, 'bot');
                        
                        // Add suggested questions if available
                        if (data.suggestions && data.suggestions.length > 0) {
                            addSuggestedQuestions(data.suggestions);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    removeTypingIndicator(typingIndicator);
                    addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                });
            }
        }
    });

    // Close chatbot when clicking outside
    document.addEventListener('click', function(e) {
        if (isChatOpen && 
            !chatbotPopup.contains(e.target) && 
            !chatbotIcon.contains(e.target)) {
            // Don't close when clicking outside
            // toggleChatbot();
        }
    });
    
    // Initialize the header with mode indicator
    const headerTitle = document.querySelector('.chatbot-header h5');
    headerTitle.innerHTML = 'LocalCarving Assistant <span class="mode-indicator">Chatbot Mode</span>';

    // Function to add suggested questions
    function addSuggestedQuestions(suggestions) {
        if (!suggestions || suggestions.length === 0) return;
        
        const suggestionsDiv = document.createElement('div');
        suggestionsDiv.className = 'suggested-questions';
        
        const title = document.createElement('p');
        title.className = 'suggestions-title';
        title.textContent = 'You might also want to ask:';
        suggestionsDiv.appendChild(title);
        
        const buttonsContainer = document.createElement('div');
        buttonsContainer.className = 'suggestion-buttons';
        
        suggestions.forEach(suggestion => {
            const button = document.createElement('button');
            button.className = 'suggestion-button';
            button.textContent = suggestion;
            button.addEventListener('click', () => {
                // Remove the suggestions
                chatMessages.removeChild(suggestionsDiv);
                
                // Add the selected question as a user message
                addMessage(suggestion, 'user');
                
                // Send the question to the chatbot
                sendMessage(suggestion);
            });
            buttonsContainer.appendChild(button);
        });
        
        suggestionsDiv.appendChild(buttonsContainer);
        chatMessages.appendChild(suggestionsDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Function to send message to chatbot API
    async function sendMessage(message) {
        // Show typing indicator
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message bot';
        typingDiv.innerHTML = '<div class="message-content"><p>Thinking...</p></div>';
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        try {
            const response = await fetch('<?php echo $base_url; ?>/api/chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            
            // Remove typing indicator
            chatMessages.removeChild(typingDiv);
            
            if (response.ok) {
                // Add bot message
                addMessage(data.response, 'bot');
                
                // Add suggested questions if available
                if (data.suggestions && data.suggestions.length > 0) {
                    addSuggestedQuestions(data.suggestions);
                }
            } else {
                // If no predefined answer found, show AI option
                showAiOption();
            }
        } catch (error) {
            console.error('Error sending message:', error);
            
            // Remove typing indicator
            chatMessages.removeChild(typingDiv);
            
            // Show error message
            addMessage("I'm sorry, I'm having trouble connecting to the server right now. Please try again later.", 'bot');
        }
    }

    // Add CSS for suggested questions
    const style = document.createElement('style');
    style.textContent = `
        .suggested-questions {
            margin: 10px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #007bff;
        }
        
        .suggestions-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #495057;
        }
        
        .suggestion-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .suggestion-button {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 15px;
            padding: 5px 12px;
            font-size: 0.9rem;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .suggestion-button:hover {
            background-color: #007bff;
            color: white;
            border-color: #0056b3;
        }
    `;
    document.head.appendChild(style);
});
</script>
<?php endif; ?> 