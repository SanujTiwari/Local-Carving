# LocalCarving Chatbot

This is a two-tier chatbot system for the LocalCarving platform that provides support for both restaurant owners and regular users.

## Features

- **Predefined Q&A**: The chatbot first tries to answer questions using a predefined set of common questions and answers.
- **AI Fallback**: If the chatbot can't find a predefined answer, it offers to use an AI model for a more detailed response.
- **Role-Based Responses**: The chatbot provides different responses based on whether the user is a restaurant owner or a regular user.
- **Floating Chat Icon**: A floating chat icon is displayed on all pages for logged-in users, providing easy access to the chatbot.

## Setup Instructions

### 1. API Key Configuration

To use the AI functionality, you need to set up an API key for the Hugging Face Inference API:

1. Sign up for a free account at [Hugging Face](https://huggingface.co/)
2. Go to your profile settings and generate an API token
3. Open `includes/ai-chat.php` and replace `'YOUR_HUGGING_FACE_API_TOKEN'` with your actual API token

```php
// Your Hugging Face API token - replace with your actual token
$api_token = 'YOUR_HUGGING_FACE_API_TOKEN';
```

### 2. Alternative AI Models

If you prefer to use a different AI model or API, you can modify the `getAiResponse` function in `includes/ai-chat.php`. The function currently uses the BlenderBot model from Facebook, but you can replace it with any other model that suits your needs.

### 3. Customizing Predefined Q&A

To add or modify the predefined questions and answers, edit the `predefinedQA` array in `chatbot.php`:

```javascript
const predefinedQA = [
    {
        question: "how do i place an order",
        answer: "To place an order, go to the Restaurants page, select a restaurant, browse their menu, add items to your cart, and proceed to checkout."
    },
    // Add more Q&A pairs here
];
```

## Usage

### For Users

1. Log in to your LocalCarving account
2. Click on the floating chat icon in the bottom right corner of any page
3. Type your question in the chat input field
4. If the chatbot can't find a predefined answer, it will offer to use the AI model
5. Choose whether to use the AI model or ask a different question

### For Restaurant Owners

1. Log in to your LocalCarving owner account
2. Click on the floating chat icon in the bottom right corner of any page
3. Ask questions about managing your restaurants, orders, or reviews
4. The chatbot will provide role-specific responses based on your owner status

## Troubleshooting

- **Chatbot not appearing**: Make sure you're logged in, as the chatbot is only available for logged-in users.
- **AI responses not working**: Check that your API key is correctly set up in `includes/ai-chat.php`.
- **Error messages**: If you see error messages, check the browser console for more details.

## Security Considerations

- The chatbot is only accessible to logged-in users
- All user inputs are sanitized to prevent XSS attacks
- API keys are stored server-side and not exposed to the client

## Future Enhancements

- Add support for image uploads in the chat
- Implement chat history storage in the database
- Add support for multiple languages
- Integrate with a more advanced AI model for better responses 