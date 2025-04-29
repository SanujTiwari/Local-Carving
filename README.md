# LocalCarving AI Chatbot

This is an AI-powered chatbot for the LocalCarving restaurant discovery and food delivery platform. The chatbot uses Google's Gemini Pro model to provide intelligent responses to user queries about restaurants, food, orders, and more.

## Features

- Real-time chat interface
- Powered by Google's Gemini Pro model
- Context-aware responses about LocalCarving services
- Modern and responsive UI
- Typing indicators and smooth animations
- PHP-based implementation
- Secure API key handling

## Localhost Setup Instructions

1. Make sure you have XAMPP, WAMP, or similar local server installed with PHP 7.4 or higher
   - Required PHP extensions: curl, json, fileinfo

2. Place the files in your local server's web directory:
   - For XAMPP: `C:\xampp\htdocs\Localcarving\AI_MODEL_CHATBOT\`
   - For WAMP: `C:\wamp64\www\Localcarving\AI_MODEL_CHATBOT\`

3. Create a `.env` file in the AI_MODEL_CHATBOT directory with your Google API key:
   ```
   GOOGLE_API_KEY=your_api_key_here
   ```

4. To get a Google API key:
   - Go to https://makersuite.google.com/app/apikey
   - Create a new API key
   - Copy the key to your .env file

5. Start your local server (Apache)

6. Access the chatbot through your browser:
   ```
   http://localhost/Localcarving/AI_MODEL_CHATBOT/chatbot.php
   ```

## Files Required

- `chatbot.php` - The main chat interface
- `chat_api.php` - The API endpoint for handling chat requests
- `.env` - Your environment configuration file with API key

## Usage

1. Open the chat interface in your browser
2. Type your questions about:
   - Restaurant discovery
   - Menu items
   - Order placement
   - Restaurant recommendations
   - Dietary restrictions
   - Order tracking
   - Account queries
   - Payment information

## Integration

To integrate this chatbot into your website:

1. Copy the contents of `chatbot.php` to your desired page
2. Update the fetch URL in the JavaScript code to match your server's path to `chat_api.php`
3. Ensure the `.env` file is properly configured with your API key
4. Make sure your web server has the required PHP extensions enabled

## Security Notes

- Keep your API key secure in the `.env` file
- Ensure the `.env` file is not accessible via web requests
- Implement rate limiting in production
- Add authentication if needed
- Use HTTPS in production

## Troubleshooting

If you encounter issues:

1. Check if Apache and PHP are running properly
2. Verify your API key is correct in the .env file
3. Ensure the curl extension is enabled in PHP
4. Check browser console for any JavaScript errors
5. Verify file permissions are set correctly
6. Make sure the .env file is readable by PHP
7. Check Apache error logs if the chatbot doesn't respond 