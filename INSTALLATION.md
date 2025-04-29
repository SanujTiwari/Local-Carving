# LocalCarving AI Chatbot Installation Guide

## Step 1: Set Up Your Local Environment

1. Make sure you have XAMPP, WAMP, or similar local server installed
2. Ensure PHP 7.4 or higher is installed with these extensions:
   - curl
   - json
   - fileinfo

## Step 2: Place Files in Your Web Directory

1. Copy all files to your web server directory:
   - For XAMPP: `C:\xampp\htdocs\Localcarving\AI_MODEL_CHATBOT\`
   - For WAMP: `C:\wamp64\www\Localcarving\AI_MODEL_CHATBOT\`

## Step 3: Create .env File

1. Create a new file named `.env` in the AI_MODEL_CHATBOT directory
2. Add your Google API key to the file:
   ```
   GOOGLE_API_KEY=your_api_key_here
   ```
3. Replace `your_api_key_here` with your actual Google Gemini API key

## Step 4: Get a Google API Key

1. Go to https://makersuite.google.com/app/apikey
2. Sign in with your Google account
3. Click "Create API Key"
4. Copy the generated API key
5. Paste it in your `.env` file

## Step 5: Start Your Local Server

1. Start Apache in your XAMPP/WAMP control panel
2. Make sure Apache is running (green light)

## Step 6: Access the Chatbot

1. Open your web browser
2. Navigate to: `http://localhost/Localcarving/AI_MODEL_CHATBOT/chatbot.php`

## Troubleshooting

If you encounter issues:

1. **API Key Issues**
   - Make sure your API key is correct in the .env file
   - Check that the .env file is in the correct location
   - Ensure the .env file is readable by PHP

2. **Server Issues**
   - Verify Apache is running
   - Check PHP error logs in XAMPP/WAMP
   - Make sure all required PHP extensions are enabled

3. **Connection Issues**
   - Check browser console for errors
   - Verify your internet connection
   - Make sure the Gemini API is accessible

4. **File Permission Issues**
   - Ensure all files have proper read permissions
   - Check that the web server user can access the files 