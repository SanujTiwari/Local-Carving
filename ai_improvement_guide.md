# Improving LocalCarving AI Responses

## Current Issues

The current AI implementation using Hugging Face's BlenderBot model is not providing specific, relevant answers about LocalCarving. The responses are generic and often don't address the actual questions being asked.

## Solutions

### Option 1: Improve the Prompt Engineering

The current prompt in `ai-chat.php` can be enhanced to provide better context to the AI:

```php
// Current implementation
$contextualized_question = "As a LocalCarving " . $role . ", " . $question . 
    " LocalCarving is a food delivery platform where users can order from local restaurants. " .
    "Restaurant owners can manage their restaurants, menus, and orders. " .
    "Users can browse restaurants, place orders, track deliveries, and leave reviews.";

// Improved implementation
$contextualized_question = "You are the LocalCarving AI assistant. LocalCarving is a food delivery platform that connects users with local restaurants. " .
    "The user asking this question is a " . $role . ". " .
    "Question: " . $question . " " .
    "Please provide a specific, detailed answer about LocalCarving's services. " .
    "If you don't know the specific answer, provide general information about LocalCarving's " .
    "features related to the question. Always mention LocalCarving in your response.";
```

### Option 2: Fine-tune the Model

To fine-tune the model with LocalCarving-specific data:

1. **Collect training data**:
   - Create a dataset of question-answer pairs specific to LocalCarving
   - Include at least 100-200 examples covering various aspects of the platform
   - Format the data according to Hugging Face's requirements

2. **Prepare the training script**:
   ```python
   from transformers import AutoModelForCausalLM, AutoTokenizer, TrainingArguments, Trainer
   from datasets import Dataset
   import pandas as pd

   # Load your dataset
   df = pd.read_csv('localcarving_qa_dataset.csv')
   dataset = Dataset.from_pandas(df)

   # Load the model and tokenizer
   model_name = "facebook/blenderbot-400M-distill"
   model = AutoModelForCausalLM.from_pretrained(model_name)
   tokenizer = AutoTokenizer.from_pretrained(model_name)

   # Define training arguments
   training_args = TrainingArguments(
       output_dir="./localcarving_model",
       num_train_epochs=3,
       per_device_train_batch_size=4,
       save_steps=1000,
       save_total_limit=2,
   )

   # Create the trainer
   trainer = Trainer(
       model=model,
       args=training_args,
       train_dataset=dataset,
   )

   # Fine-tune the model
   trainer.train()

   # Save the model
   model.save_pretrained("./localcarving_model")
   tokenizer.save_pretrained("./localcarving_model")
   ```

3. **Update the API endpoint** in `ai-chat.php`:
   ```php
   // Update the API endpoint to use your fine-tuned model
   $api_url = 'https://api-inference.huggingface.co/models/YOUR_USERNAME/localcarving_model';
   ```

### Option 3: Use a More Capable Model

Consider switching to a more capable model like GPT-3 or GPT-4:

1. **Sign up for OpenAI API**:
   - Create an account at [OpenAI](https://openai.com/)
   - Generate an API key

2. **Update the `getAiResponse` function** in `ai-chat.php`:
   ```php
   function getAiResponse($question, $role) {
       // OpenAI API endpoint
       $api_url = 'https://api.openai.com/v1/chat/completions';
       
       // Your OpenAI API key
       $api_key = 'YOUR_OPENAI_API_KEY';
       
       // Add context to the question
       $contextualized_question = "You are the LocalCarving AI assistant. LocalCarving is a food delivery platform that connects users with local restaurants. " .
           "The user asking this question is a " . $role . ". " .
           "Question: " . $question;
       
       // Prepare the request data
       $data = json_encode([
           'model' => 'gpt-3.5-turbo',
           'messages' => [
               [
                   'role' => 'system',
                   'content' => 'You are the LocalCarving AI assistant. Provide specific, detailed answers about LocalCarving\'s services. Always mention LocalCarving in your response.'
               ],
               [
                   'role' => 'user',
                   'content' => $contextualized_question
               ]
           ],
           'temperature' => 0.7,
           'max_tokens' => 150
       ]);
       
       // Initialize cURL session
       $ch = curl_init($api_url);
       
       // Set cURL options
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       curl_setopt($ch, CURLOPT_HTTPHEADER, [
           'Authorization: Bearer ' . $api_key,
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
       if (isset($result['choices'][0]['message']['content'])) {
           return $result['choices'][0]['message']['content'];
       } else {
           // If we couldn't get a valid response, provide a generic response
           return getDomainSpecificResponse($question, $role);
       }
   }
   ```

## Creating a Training Dataset

To create a comprehensive training dataset for fine-tuning:

1. **Use the test questions** from `ai_test_questions.txt`
2. **Create detailed answers** for each question
3. **Format the data** as a CSV file with columns: `question`, `answer`, `role`
4. **Include role-specific information** for both regular users and restaurant owners

Example of a training data entry:
```
question,answer,role
"What is LocalCarving?","LocalCarving is a food delivery platform that connects users with local restaurants. It allows users to browse menus, place orders, track deliveries, and leave reviews. Restaurant owners can manage their restaurants, menus, and orders through the platform. LocalCarving focuses on supporting local businesses and providing a seamless food ordering experience.",user
"How do I add my restaurant to LocalCarving?","As a restaurant owner, you can add your restaurant to LocalCarving by going to your dashboard and clicking on 'Add Restaurant'. You'll need to provide basic information like your restaurant's name, address, cuisine type, and operating hours. You'll also need to upload photos of your restaurant and menu items. Once submitted, the LocalCarving team will review your application and get back to you within 2-3 business days. After approval, you can start managing your menu and accepting orders.",owner
```

## Testing the Improved AI

After implementing any of these solutions, test the AI with the questions in `ai_test_questions.txt` to verify that the responses are more specific and relevant to LocalCarving.

## Additional Resources

- [Hugging Face Fine-tuning Guide](https://huggingface.co/docs/transformers/training)
- [OpenAI API Documentation](https://platform.openai.com/docs/api-reference)
- [Prompt Engineering Guide](https://www.promptingguide.ai/) 