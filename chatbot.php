<?php
// Start output buffering
ob_start();

require_once 'includes/session.php';
require_once 'includes/db.php';
require_once 'includes/ai-chat.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Include header
require_once 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">LocalCarving Support Chat</h4>
                </div>
                <div class="card-body">
                    <div id="chat-container" class="chat-container mb-3" style="height: 400px; overflow-y: auto;">
                        <div class="chat-message system">
                            <div class="message-content">
                                <p>Hello <?php echo htmlspecialchars($username); ?>! How can I help you today?</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-input-container">
                        <form id="chat-form" class="d-flex">
                            <input type="text" id="user-input" class="form-control me-2" placeholder="Type your message here..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div id="ai-option" class="mt-3 d-none">
                        <div class="alert alert-info">
                            <p>I couldn't find a specific answer to your question. Would you like to use our AI assistant for a more detailed response?</p>
                            <div class="d-flex justify-content-end">
                                <button id="use-ai-yes" class="btn btn-primary me-2">Yes, use AI</button>
                                <button id="use-ai-no" class="btn btn-secondary">No, ask something else</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-container {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        padding: 15px;
        background-color: #f9f9f9;
    }
    
    .chat-message {
        margin-bottom: 15px;
        display: flex;
    }
    
    .chat-message.user {
        justify-content: flex-end;
    }
    
    .message-content {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 18px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .user .message-content {
        background-color: #007bff;
        color: white;
        border-bottom-right-radius: 5px;
    }
    
    .bot .message-content {
        background-color: white;
        color: #333;
        border-bottom-left-radius: 5px;
    }
    
    .system .message-content {
        background-color: #f0f0f0;
        color: #666;
        border-radius: 5px;
        text-align: center;
        width: 100%;
    }
    
    .chat-input-container {
        margin-top: 15px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-container');
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const aiOption = document.getElementById('ai-option');
        const useAiYes = document.getElementById('use-ai-yes');
        const useAiNo = document.getElementById('use-ai-no');
        
        let lastUserMessage = '';
        let waitingForAiResponse = false;
        
        // Predefined Q&A pairs
        const predefinedQA = [
            {
                question: "how do i place an order",
                answer: "To place an order, go to the Restaurants page, select a restaurant, browse their menu, add items to your cart, and proceed to checkout."
            },
            {
                question: "how do i track my order",
                answer: "You can track your order by going to 'My Orders' in your dashboard. There you'll see the status of all your orders."
            },
            {
                question: "how do i add a restaurant",
                answer: "As a restaurant owner, you can add a new restaurant by going to your dashboard and clicking on 'Add Restaurant'. Fill in the required details and submit."
            },
            {
                question: "how do i manage orders",
                answer: "Restaurant owners can manage orders by going to the 'Orders' section in their dashboard. There you can view, confirm, and update the status of orders."
            },
            {
                question: "how do i leave a review",
                answer: "After placing and receiving an order, you can leave a review by going to 'My Orders', finding the completed order, and clicking on 'Leave Review'."
            },
            {
                question: "how do i change my password",
                answer: "You can change your password by going to your profile settings. Click on your username in the top right corner and select 'Profile Settings'."
            },
            {
                question: "what payment methods do you accept",
                answer: "We currently accept credit/debit cards, PayPal, and cash on delivery for orders."
            },
            {
                question: "how do i contact support",
                answer: "You can contact our support team by emailing support@localcarving.com or by using this chat interface."
            }
        ];
        
        // Function to add a message to the chat
        function addMessage(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${sender}`;
            
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            
            const paragraph = document.createElement('p');
            paragraph.textContent = message;
            
            contentDiv.appendChild(paragraph);
            messageDiv.appendChild(contentDiv);
            chatContainer.appendChild(messageDiv);
            
            // Scroll to bottom
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        
        // Function to check if a question matches predefined Q&A
        function findPredefinedAnswer(question) {
            question = question.toLowerCase().trim();
            
            for (const qa of predefinedQA) {
                if (question.includes(qa.question)) {
                    return qa.answer;
                }
            }
            
            return null;
        }
        
        // Function to get AI response from server
        async function getAiResponse(question) {
            try {
                const response = await fetch('get-ai-response.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        question: question,
                        role: '<?php echo $role; ?>'
                    })
                });
                
                const data = await response.json();
                return data.response;
            } catch (error) {
                console.error('Error getting AI response:', error);
                return "I'm sorry, I'm having trouble connecting to the AI service right now. Please try again later or ask a different question.";
            }
        }
        
        // Handle form submission
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = userInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessage(message, 'user');
            lastUserMessage = message;
            
            // Clear input
            userInput.value = '';
            
            // Check if we're waiting for AI response
            if (waitingForAiResponse) {
                waitingForAiResponse = false;
                aiOption.classList.add('d-none');
                
                // Show typing indicator
                const typingDiv = document.createElement('div');
                typingDiv.className = 'chat-message bot';
                typingDiv.innerHTML = '<div class="message-content"><p>Thinking...</p></div>';
                chatContainer.appendChild(typingDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight;
                
                // Get AI response
                const aiResponse = await getAiResponse(message);
                
                // Remove typing indicator
                chatContainer.removeChild(typingDiv);
                
                // Add AI response
                addMessage(aiResponse, 'bot');
                return;
            }
            
            // Check predefined Q&A first
            const predefinedAnswer = findPredefinedAnswer(message);
            
            if (predefinedAnswer) {
                // Add bot message with predefined answer
                addMessage(predefinedAnswer, 'bot');
            } else {
                // Show AI option
                aiOption.classList.remove('d-none');
            }
        });
        
        // Handle AI option buttons
        useAiYes.addEventListener('click', async function() {
            aiOption.classList.add('d-none');
            waitingForAiResponse = true;
            
            // Show typing indicator
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chat-message bot';
            typingDiv.innerHTML = '<div class="message-content"><p>Thinking...</p></div>';
            chatContainer.appendChild(typingDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
            
            // Get AI response
            const aiResponse = await getAiResponse(lastUserMessage);
            
            // Remove typing indicator
            chatContainer.removeChild(typingDiv);
            
            // Add AI response
            addMessage(aiResponse, 'bot');
        });
        
        useAiNo.addEventListener('click', function() {
            aiOption.classList.add('d-none');
            addMessage("Please ask a different question.", 'bot');
        });
    });
</script>

<?php
// Include footer
require_once 'includes/footer.php';

// Flush the output buffer
ob_end_flush();
?> 