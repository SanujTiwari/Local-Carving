<?php
// This file handles AI responses for the chatbot
// It uses the Hugging Face Inference API which offers free access to various AI models

function getAiResponse($question, $role) {
    // API endpoint for Hugging Face Inference API
    $api_url = 'https://api-inference.huggingface.co/models/facebook/blenderbot-400M-distill';
    
    // Your Hugging Face API token - replace with your actual token
    $api_token = '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';
    
    // Add context to the question to help the AI understand it's about LocalCarving
    $contextualized_question = "As a LocalCarving " . $role . ", " . $question . 
        " LocalCarving is a food delivery platform where users can order from local restaurants. " .
        "Restaurant owners can manage their restaurants, menus, and orders. " .
        "Users can browse restaurants, place orders, track deliveries, and leave reviews.";
    
    // Prepare the request data
    $data = json_encode([
        'inputs' => $contextualized_question,
        'parameters' => [
            'max_length' => 150,
            'temperature' => 0.7,
            'top_p' => 0.9,
            'do_sample' => true
        ]
    ]);
    
    // Initialize cURL session
    $ch = curl_init($api_url);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_token,
        'Content-Type: application/json'
    ]);
    
    // Execute the request
    $response = curl_exec($ch);
    
    // Check for errors
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return "I'm sorry, I'm having trouble connecting to the AI service right now. Please try again later.";
    }
    
    // Close cURL session
    curl_close($ch);
    
    // Decode the response
    $result = json_decode($response, true);
    
    // Check if we got a valid response
    if (isset($result[0]['generated_text'])) {
        $ai_response = $result[0]['generated_text'];
        
        // Filter the response to ensure it's related to LocalCarving
        if (isRelevantToLocalCarving($ai_response)) {
            return $ai_response;
        } else {
            // If the response is not relevant, provide a generic response
            return getDomainSpecificResponse($question, $role);
        }
    } else {
        // If we couldn't get a valid response, provide a generic response
        return getDomainSpecificResponse($question, $role);
    }
}

// Function to check if the AI response is relevant to LocalCarving
function isRelevantToLocalCarving($response) {
    $keywords = [
        // Food and restaurant related
        'food', 'restaurant', 'order', 'delivery', 'menu', 'dish', 'meal', 
        'cuisine', 'chef', 'cooking', 'recipe', 'ingredient', 'taste', 'flavor',
        'appetizer', 'entree', 'dessert', 'beverage', 'drink', 'wine', 'beer',
        'breakfast', 'lunch', 'dinner', 'snack', 'takeout', 'dine-in', 'reservation',
        
        // LocalCarving specific
        'localcarving', 'local carving', 'local food', 'local restaurant',
        'platform', 'service', 'app', 'website', 'online', 'digital',
        
        // User related
        'customer', 'user', 'client', 'patron', 'guest', 'visitor',
        'account', 'profile', 'login', 'register', 'password', 'email', 'code',
        'preference', 'favorite', 'history', 'saved', 'bookmark',
        
        // Owner related
        'owner', 'manager', 'business', 'establishment', 'venue', 'location',
        'hours', 'operation', 'staff', 'employee', 'kitchen', 'service',
        
        // Order related
        'cart', 'checkout', 'payment', 'bill', 'receipt', 'invoice', 'transaction',
        'credit card', 'debit card', 'cash', 'wallet', 'promo', 'discount', 'coupon',
        'delivery fee', 'service fee', 'tax', 'tip', 'driver', 'delivery person',
        
        // Review related
        'review', 'rating', 'star', 'feedback', 'comment', 'opinion', 'experience',
        'satisfaction', 'recommendation', 'suggestion', 'complaint', 'praise',
        
        // General terms
        'help', 'support', 'assist', 'guide', 'explain', 'tell', 'show', 'how to',
        'what is', 'where is', 'when', 'why', 'can i', 'do you', 'is there',
        'available', 'possible', 'option', 'alternative', 'choice', 'selection',
        'price', 'cost', 'expensive', 'cheap', 'affordable', 'budget', 'value',
        'quality', 'fresh', 'healthy', 'organic', 'vegetarian', 'vegan', 'gluten-free',
        'allergy', 'dietary', 'restriction', 'special', 'custom', 'personalized'
    ];
    
    $response_lower = strtolower($response);
    
    // Count how many keywords are found in the response
    $keyword_count = 0;
    foreach ($keywords as $keyword) {
        if (strpos($response_lower, $keyword) !== false) {
            $keyword_count++;
        }
    }
    
    // If at least 2 keywords are found, consider the response relevant
    return $keyword_count >= 2;
}

// Function to provide domain-specific responses when AI fails
function getDomainSpecificResponse($question, $role) {
    $question_lower = strtolower($question);
    
    // Common questions for both users and owners
    if (strpos($question_lower, 'help') !== false || strpos($question_lower, 'support') !== false) {
        return "I'm here to help! You can ask me questions about ordering food, managing your account, or using LocalCarving's features.";
    }
    
    if (strpos($question_lower, 'contact') !== false) {
        return "You can contact our support team by emailing support@localcarving.com or by using this chat interface.";
    }
    
    if (strpos($question_lower, 'name') !== false || strpos($question_lower, 'who are you') !== false) {
        return "I'm the LocalCarving Assistant, here to help you with any questions about our food delivery platform.";
    }
    
    if (strpos($question_lower, 'hello') !== false || strpos($question_lower, 'hi') !== false || 
        strpos($question_lower, 'hey') !== false || strpos($question_lower, 'greetings') !== false) {
        return "Hello! How can I assist you with LocalCarving today?";
    }
    
    if (strpos($question_lower, 'thank') !== false || strpos($question_lower, 'thanks') !== false) {
        return "You're welcome! Is there anything else I can help you with?";
    }
    
    if (strpos($question_lower, 'bye') !== false || strpos($question_lower, 'goodbye') !== false) {
        return "Goodbye! Have a great day and enjoy your LocalCarving experience!";
    }
    
    if (strpos($question_lower, 'food') !== false || strpos($question_lower, 'eat') !== false || 
        strpos($question_lower, 'hungry') !== false || strpos($question_lower, 'meal') !== false) {
        return "LocalCarving offers a wide variety of food options from local restaurants. You can browse restaurants by cuisine, rating, or location to find the perfect meal for you.";
    }
    
    if (strpos($question_lower, 'price') !== false || strpos($question_lower, 'cost') !== false || 
        strpos($question_lower, 'expensive') !== false || strpos($question_lower, 'cheap') !== false) {
        return "LocalCarving restaurants offer a range of price points to suit different budgets. You can filter restaurants by price level, and many offer special deals and promotions.";
    }
    
    if (strpos($question_lower, 'delivery') !== false || strpos($question_lower, 'deliver') !== false || 
        strpos($question_lower, 'time') !== false || strpos($question_lower, 'when') !== false) {
        return "Delivery times vary by restaurant and location. When you place an order, you'll receive an estimated delivery time. You can track your order in real-time through the 'My Orders' section.";
    }
    
    if (strpos($question_lower, 'payment') !== false || strpos($question_lower, 'pay') !== false || 
        strpos($question_lower, 'card') !== false || strpos($question_lower, 'cash') !== false) {
        return "LocalCarving accepts various payment methods including credit/debit cards, PayPal, and cash on delivery for some restaurants. You can manage your payment methods in your account settings.";
    }
    
    if (strpos($question_lower, 'discount') !== false || strpos($question_lower, 'promo') !== false || 
        strpos($question_lower, 'offer') !== false || strpos($question_lower, 'deal') !== false) {
        return "LocalCarving regularly offers discounts and promotions. You can find current deals on the homepage or in the 'Promotions' section. Some restaurants also offer their own special deals.";
    }
    
    if (strpos($question_lower, 'restaurant') !== false || strpos($question_lower, 'place') !== false || 
        strpos($question_lower, 'venue') !== false || strpos($question_lower, 'establishment') !== false) {
        return "LocalCarving features a variety of local restaurants. You can browse by cuisine type, rating, or location. Each restaurant page shows their menu, reviews, and other important information.";
    }
    
    // Role-specific responses
    if ($role === 'owner') {
        if (strpos($question_lower, 'restaurant') !== false || strpos($question_lower, 'add') !== false) {
            return "As a restaurant owner, you can add a new restaurant by going to your dashboard and clicking on 'Add Restaurant'. Fill in the required details and submit.";
        }
        
        if (strpos($question_lower, 'order') !== false || strpos($question_lower, 'manage') !== false) {
            return "You can manage orders by going to the 'Orders' section in your dashboard. There you can view, confirm, and update the status of orders.";
        }
        
        if (strpos($question_lower, 'review') !== false) {
            return "You can view and respond to customer reviews in the 'Reviews' section of your dashboard.";
        }
        
        if (strpos($question_lower, 'menu') !== false || strpos($question_lower, 'item') !== false || 
            strpos($question_lower, 'dish') !== false || strpos($question_lower, 'food') !== false) {
            return "You can manage your restaurant's menu in the 'Menu' section of your dashboard. There you can add, edit, or remove items, update prices, and manage availability.";
        }
        
        if (strpos($question_lower, 'analytics') !== false || strpos($question_lower, 'stats') !== false || 
            strpos($question_lower, 'performance') !== false || strpos($question_lower, 'report') !== false) {
            return "You can view your restaurant's performance analytics in the 'Analytics' section of your dashboard. There you'll find data on orders, revenue, customer satisfaction, and more.";
        }
        
        if (strpos($question_lower, 'profile') !== false || strpos($question_lower, 'information') !== false || 
            strpos($question_lower, 'details') !== false || strpos($question_lower, 'update') !== false) {
            return "You can update your restaurant's profile information in the 'Profile' section of your dashboard. There you can edit your restaurant's name, description, location, hours, and more.";
        }
    } else {
        if (strpos($question_lower, 'order') !== false || strpos($question_lower, 'place') !== false) {
            return "To place an order, go to the Restaurants page, select a restaurant, browse their menu, add items to your cart, and proceed to checkout.";
        }
        
        if (strpos($question_lower, 'track') !== false) {
            return "You can track your order by going to 'My Orders' in your dashboard. There you'll see the status of all your orders.";
        }
        
        if (strpos($question_lower, 'favorite') !== false) {
            return "You can save your favorite restaurants by clicking the heart icon on any restaurant page. View your favorites in the 'Favorites' section of your dashboard.";
        }
        
        if (strpos($question_lower, 'review') !== false || strpos($question_lower, 'rate') !== false || 
            strpos($question_lower, 'feedback') !== false || strpos($question_lower, 'comment') !== false) {
            return "After receiving your order, you can leave a review by going to 'My Orders', finding the completed order, and clicking on 'Leave Review'. Your feedback helps other customers and the restaurant.";
        }
        
        if (strpos($question_lower, 'account') !== false || strpos($question_lower, 'profile') !== false || 
            strpos($question_lower, 'settings') !== false || strpos($question_lower, 'preferences') !== false) {
            return "You can manage your account settings by clicking on your profile picture in the top right corner and selecting 'Account Settings'. There you can update your personal information, delivery addresses, and preferences.";
        }
        
        if (strpos($question_lower, 'address') !== false || strpos($question_lower, 'location') !== false || 
            strpos($question_lower, 'delivery') !== false || strpos($question_lower, 'where') !== false) {
            return "You can manage your delivery addresses in the 'Addresses' section of your account settings. You can add multiple addresses and set a default address for quick checkout.";
        }
    }
    
    // Default response with more context
    return "I'm not sure I understand your question. Could you please rephrase it or ask something related to LocalCarving's services? You can ask about ordering food, managing your account, restaurant information, delivery options, payment methods, or any other aspect of our platform.";
}
?> 