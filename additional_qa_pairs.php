<?php
/**
 * Additional Q&A pairs for LocalCarving chatbot
 * 
 * This file contains additional question-answer pairs that can be integrated
 * into the chatbot.php file to enhance the chatbot's knowledge base.
 */

// Food Ordering Questions
$food_ordering_qa = [
    [
        'question' => 'how do i place an order',
        'answer' => 'To place an order, go to the Restaurants page, select a restaurant, browse their menu, add items to your cart, and proceed to checkout.',
        'suggestions' => ['how do i track my order', 'what payment methods do you accept', 'can i customize my order']
    ],
    [
        'question' => 'how do i track my order',
        'answer' => 'You can track your order by going to "My Orders" in your dashboard. There you\'ll see the status of all your orders.',
        'suggestions' => ['how long does delivery take', 'what happens if my order is late', 'can i cancel my order']
    ],
    [
        'question' => 'what payment methods do you accept',
        'answer' => 'We accept credit/debit cards, PayPal, and cash on delivery for some restaurants.',
        'suggestions' => ['is my payment information secure', 'can i save payment methods', 'do you offer any discounts']
    ],
    [
        'question' => 'can i customize my order',
        'answer' => 'Yes, you can add special instructions when placing your order. Many restaurants allow you to customize items (e.g., "no onions", "extra spicy").',
        'suggestions' => ['how do i add special instructions', 'can i request specific cooking preferences', 'what if i have food allergies']
    ],
    [
        'question' => 'how long does delivery take',
        'answer' => 'Delivery times vary by restaurant and your location. Most orders arrive within 30-45 minutes. You can see estimated delivery times when placing your order.',
        'suggestions' => ['what happens if my order is late', 'can i schedule a future delivery', 'is there a minimum order amount']
    ],
    [
        'question' => 'what happens if my order is late',
        'answer' => 'If your order is significantly delayed, you can contact our customer support. We work with restaurants to ensure timely delivery and may offer compensation for excessive delays.',
        'suggestions' => ['how do i contact customer support', 'can i get a refund for a late order', 'how do i report delivery issues']
    ],
    [
        'question' => 'can i cancel my order',
        'answer' => 'You can cancel your order if the restaurant hasn\'t started preparing it yet. Go to "My Orders" and look for the cancel option. If the restaurant has already started preparing your order, you may need to contact customer support.',
        'suggestions' => ['what is your refund policy', 'how do i get a refund', 'can i modify my order instead of canceling']
    ],
    [
        'question' => 'is my payment information secure',
        'answer' => 'Yes, we use industry-standard encryption to protect your payment information. We never store your full credit card details on our servers.',
        'suggestions' => ['can i save payment methods', 'how do i update my payment information', 'what happens if there\'s a payment error']
    ],
    [
        'question' => 'can i save payment methods',
        'answer' => 'Yes, you can save multiple payment methods to your account for faster checkout. Go to your profile settings and select "Payment Methods" to add or manage your saved payment options.',
        'suggestions' => ['how do i update my payment information', 'is my payment information secure', 'what payment methods do you accept']
    ],
    [
        'question' => 'do you offer any discounts',
        'answer' => 'Yes, we offer various discounts and promotions. Check the "Promotions" section on our homepage or look for special offers when browsing restaurants. You can also sign up for our newsletter to receive exclusive deals.',
        'suggestions' => ['how do i apply a promo code', 'are there any first-time user discounts', 'do you have a loyalty program']
    ],
    [
        'question' => 'how do i add special instructions',
        'answer' => 'When placing your order, you\'ll see a "Special Instructions" field where you can add any specific requests for the restaurant. You can also add instructions for individual items when adding them to your cart.',
        'suggestions' => ['can i customize my order', 'what if i have food allergies', 'can i request specific cooking preferences']
    ],
    [
        'question' => 'can i request specific cooking preferences',
        'answer' => 'Yes, you can request specific cooking preferences in the special instructions field. For example, you can ask for "well-done", "rare", or "medium" for meat items.',
        'suggestions' => ['how do i add special instructions', 'what if i have food allergies', 'can i customize my order']
    ],
    [
        'question' => 'what if i have food allergies',
        'answer' => 'If you have food allergies, please clearly specify them in the special instructions when placing your order. You can also contact the restaurant directly through our platform to discuss your dietary requirements.',
        'suggestions' => ['do you have gluten-free options', 'are there vegetarian restaurants', 'can i request allergen information']
    ],
    [
        'question' => 'can i schedule a future delivery',
        'answer' => 'Yes, you can schedule orders for future delivery. When checking out, look for the "Schedule Delivery" option to select your preferred delivery date and time.',
        'suggestions' => ['how far in advance can i schedule', 'can i modify a scheduled order', 'what happens if i need to cancel a scheduled order']
    ],
    [
        'question' => 'is there a minimum order amount',
        'answer' => 'Minimum order amounts vary by restaurant. You\'ll see the minimum order amount clearly displayed when browsing a restaurant\'s menu.',
        'suggestions' => ['do you have delivery fees', 'are there any service charges', 'do you offer any discounts']
    ],
    [
        'question' => 'how do i contact customer support',
        'answer' => 'You can contact our customer support team through the "Help" section in your account, by emailing support@localcarving.com, or by using the chat feature on our website.',
        'suggestions' => ['what are your support hours', 'how do i report an issue with my order', 'can i speak to a human representative']
    ],
    [
        'question' => 'can i get a refund for a late order',
        'answer' => 'If your order is significantly delayed, you may be eligible for a refund or credit. Contact our customer support team with your order details, and we\'ll assist you with the refund process.',
        'suggestions' => ['how do i contact customer support', 'what is your refund policy', 'how long does it take to process a refund']
    ],
    [
        'question' => 'how do i report delivery issues',
        'answer' => 'You can report delivery issues through the "Help" section in your account. Select your order and describe the issue. Our customer support team will investigate and assist you accordingly.',
        'suggestions' => ['how do i contact customer support', 'what happens if my order is missing items', 'can i get a refund for a problematic order']
    ],
    [
        'question' => 'what is your refund policy',
        'answer' => 'Our refund policy varies depending on the issue. For canceled orders before preparation, you\'ll receive a full refund. For issues with delivered orders, we evaluate each case individually and may offer partial or full refunds.',
        'suggestions' => ['how do i request a refund', 'how long does it take to process a refund', 'what happens if i dispute a charge']
    ],
    [
        'question' => 'how do i get a refund',
        'answer' => 'To request a refund, go to "My Orders" in your account, select the relevant order, and click on "Request Refund." Provide details about why you\'re requesting a refund, and our team will review your request.',
        'suggestions' => ['what is your refund policy', 'how long does it take to process a refund', 'can i cancel my order instead']
    ],
    [
        'question' => 'can i modify my order instead of canceling',
        'answer' => 'If you need to modify your order after placing it, contact the restaurant directly through our platform as soon as possible. If they haven\'t started preparing your order, they may be able to accommodate your changes.',
        'suggestions' => ['how do i contact the restaurant', 'what if the restaurant can\'t modify my order', 'can i cancel my order']
    ],
    [
        'question' => 'how do i update my payment information',
        'answer' => 'You can update your payment information in your account settings. Go to "Payment Methods" and select "Edit" next to the payment method you want to update.',
        'suggestions' => ['can i save payment methods', 'is my payment information secure', 'what payment methods do you accept']
    ],
    [
        'question' => 'what happens if there\'s a payment error',
        'answer' => 'If you encounter a payment error, try using a different payment method or check if your card has sufficient funds. If the issue persists, contact our customer support team for assistance.',
        'suggestions' => ['how do i contact customer support', 'can i use a different payment method', 'is my payment information secure']
    ],
    [
        'question' => 'how do i apply a promo code',
        'answer' => 'To apply a promo code, enter it in the "Promo Code" field during checkout. If the code is valid, the discount will be automatically applied to your order total.',
        'suggestions' => ['do you offer any discounts', 'are there any first-time user discounts', 'do you have a loyalty program']
    ],
    [
        'question' => 'are there any first-time user discounts',
        'answer' => 'Yes, we often offer special discounts for first-time users. Check our homepage or the "Promotions" section for current first-time user offers.',
        'suggestions' => ['how do i apply a promo code', 'do you offer any discounts', 'do you have a loyalty program']
    ],
    [
        'question' => 'do you have a loyalty program',
        'answer' => 'Yes, we have a loyalty program where you can earn points for every order. These points can be redeemed for discounts on future orders. You can view your points balance in your account dashboard.',
        'suggestions' => ['how do i redeem my loyalty points', 'how many points do i earn per order', 'are there any special loyalty promotions']
    ],
    [
        'question' => 'how far in advance can i schedule',
        'answer' => 'You can schedule orders up to 7 days in advance. This allows you to plan ahead for events or busy days when you won\'t have time to cook.',
        'suggestions' => ['can i modify a scheduled order', 'what happens if i need to cancel a scheduled order', 'can i schedule a future delivery']
    ],
    [
        'question' => 'can i modify a scheduled order',
        'answer' => 'Yes, you can modify a scheduled order as long as it\'s at least 2 hours before the scheduled delivery time. Go to "My Orders," find your scheduled order, and click on "Modify Order."',
        'suggestions' => ['what happens if i need to cancel a scheduled order', 'how far in advance can i schedule', 'can i schedule a future delivery']
    ],
    [
        'question' => 'what happens if i need to cancel a scheduled order',
        'answer' => 'You can cancel a scheduled order up to 2 hours before the scheduled delivery time. Go to "My Orders," find your scheduled order, and click on "Cancel Order." If it\'s less than 2 hours before delivery, contact customer support.',
        'suggestions' => ['how do i contact customer support', 'what is your refund policy', 'can i modify a scheduled order instead']
    ],
    [
        'question' => 'do you have delivery fees',
        'answer' => 'Delivery fees vary by restaurant and your location. The fee is clearly displayed when you place your order. Some restaurants offer free delivery for orders above a certain amount.',
        'suggestions' => ['is there a minimum order amount', 'are there any service charges', 'do you offer any discounts']
    ],
    [
        'question' => 'are there any service charges',
        'answer' => 'Some restaurants may apply a service charge to cover operational costs. Any service charges will be clearly displayed during checkout before you confirm your order.',
        'suggestions' => ['do you have delivery fees', 'is there a minimum order amount', 'do you offer any discounts']
    ],
    [
        'question' => 'what are your support hours',
        'answer' => 'Our customer support team is available 24/7 to assist you with any issues. You can reach us through the chat feature on our website, by email, or through the "Help" section in your account.',
        'suggestions' => ['how do i contact customer support', 'can i speak to a human representative', 'how do i report an issue with my order']
    ],
    [
        'question' => 'how do i report an issue with my order',
        'answer' => 'To report an issue with your order, go to "My Orders" in your account, select the relevant order, and click on "Report Issue." Provide details about the problem, and our customer support team will assist you.',
        'suggestions' => ['how do i contact customer support', 'what happens if my order is missing items', 'can i get a refund for a problematic order']
    ],
    [
        'question' => 'can i speak to a human representative',
        'answer' => 'Yes, you can speak to a human representative by contacting our customer support team through the chat feature on our website. During peak hours, there might be a short wait time.',
        'suggestions' => ['what are your support hours', 'how do i contact customer support', 'how do i report an issue with my order']
    ],
    [
        'question' => 'what happens if my order is missing items',
        'answer' => 'If your order is missing items, please report the issue through the "Help" section in your account. Select your order and describe the missing items. Our customer support team will investigate and may offer a refund or replacement.',
        'suggestions' => ['how do i report an issue with my order', 'can i get a refund for a problematic order', 'how do i contact customer support']
    ],
    [
        'question' => 'how long does it take to process a refund',
        'answer' => 'Refunds typically process within 3-5 business days, depending on your payment method and bank. Credit card refunds may take up to 10 business days to appear on your statement.',
        'suggestions' => ['what is your refund policy', 'how do i request a refund', 'what happens if i dispute a charge']
    ],
    [
        'question' => 'what happens if i dispute a charge',
        'answer' => 'If you believe a charge is incorrect, please contact our customer support team first. If we can\'t resolve the issue, you can dispute the charge with your bank or credit card company. We\'ll cooperate with any investigation.',
        'suggestions' => ['how do i contact customer support', 'what is your refund policy', 'how do i request a refund']
    ],
    [
        'question' => 'how do i contact the restaurant',
        'answer' => 'You can contact a restaurant through our platform by going to their page and clicking on the "Contact" button. This will open a chat window where you can send a message to the restaurant.',
        'suggestions' => ['can i modify my order instead of canceling', 'what if the restaurant can\'t modify my order', 'how do i leave a review for a restaurant']
    ],
    [
        'question' => 'what if the restaurant can\'t modify my order',
        'answer' => 'If the restaurant can\'t modify your order, you may need to cancel it and place a new order with the desired changes. If you\'ve already paid, you\'ll receive a refund for the canceled order.',
        'suggestions' => ['how do i cancel my order', 'what is your refund policy', 'how do i contact the restaurant']
    ],
    [
        'question' => 'how do i leave a review for a restaurant',
        'answer' => 'After receiving your order, you can leave a review by going to "My Orders" in your account, finding the completed order, and clicking on "Leave Review." You can rate the restaurant and provide feedback about your experience.',
        'suggestions' => ['can i edit my review after submitting it', 'how do restaurant ratings work', 'what happens if i report an issue with my order']
    ],
    [
        'question' => 'can i edit my review after submitting it',
        'answer' => 'Yes, you can edit your review within 7 days of submitting it. Go to "My Reviews" in your account, find the review you want to edit, and click on "Edit."',
        'suggestions' => ['how do i leave a review for a restaurant', 'how do restaurant ratings work', 'can i delete my review']
    ],
    [
        'question' => 'how do restaurant ratings work',
        'answer' => 'Restaurant ratings are based on customer reviews and feedback. The overall rating is calculated as an average of all reviews. Ratings consider factors like food quality, delivery time, and customer service.',
        'suggestions' => ['how do i leave a review for a restaurant', 'can i edit my review after submitting it', 'what happens if i report an issue with my order']
    ],
    [
        'question' => 'can i delete my review',
        'answer' => 'Yes, you can delete your review by going to "My Reviews" in your account, finding the review you want to delete, and clicking on "Delete." Please note that deleted reviews cannot be recovered.',
        'suggestions' => ['how do i leave a review for a restaurant', 'can i edit my review after submitting it', 'how do restaurant ratings work']
    ],
    [
        'question' => 'how do i redeem my loyalty points',
        'answer' => 'To redeem your loyalty points, go to the checkout page when placing an order. You\'ll see an option to apply your points for a discount. Select the amount of points you want to redeem, and the discount will be applied to your order total.',
        'suggestions' => ['how many points do i earn per order', 'are there any special loyalty promotions', 'do you have a loyalty program']
    ],
    [
        'question' => 'how many points do i earn per order',
        'answer' => 'You earn 1 point for every $1 spent on orders. Some special promotions may offer bonus points for specific restaurants or during certain periods.',
        'suggestions' => ['how do i redeem my loyalty points', 'are there any special loyalty promotions', 'do you have a loyalty program']
    ],
    [
        'question' => 'are there any special loyalty promotions',
        'answer' => 'Yes, we occasionally run special promotions where you can earn bonus loyalty points. Check the "Promotions" section on our homepage or your account dashboard for current loyalty promotions.',
        'suggestions' => ['how do i redeem my loyalty points', 'how many points do i earn per order', 'do you have a loyalty program']
    ],
];

// Restaurant and Food Related Questions
$restaurant_food_qa = [
    [
        'question' => 'what types of restaurants do you have',
        'answer' => 'We have a wide variety of restaurants including local eateries, cafes, fast food, fine dining, and international cuisine. You can browse restaurants by cuisine type, rating, or location.',
        'suggestions' => ['do you have vegetarian restaurants', 'what cuisines are available', 'how do i find restaurants near me']
    ],
    [
        'question' => 'do you have vegetarian restaurants',
        'answer' => 'Yes, we have many vegetarian restaurants. You can filter restaurants by dietary preferences to find vegetarian options. Many non-vegetarian restaurants also offer vegetarian dishes.',
        'suggestions' => ['do you have vegan options', 'what types of restaurants do you have', 'how do i find restaurants with specific dietary options']
    ],
    [
        'question' => 'do you have vegan options',
        'answer' => 'Yes, we have restaurants that specialize in vegan cuisine. You can filter restaurants by dietary preferences to find vegan options. Many restaurants also offer vegan dishes on their regular menu.',
        'suggestions' => ['do you have vegetarian restaurants', 'do you have gluten-free options', 'how do i find restaurants with specific dietary options']
    ],
    [
        'question' => 'do you have gluten-free options',
        'answer' => 'Yes, we have restaurants that offer gluten-free options. You can filter restaurants by dietary preferences to find gluten-free options. Many restaurants clearly mark gluten-free items on their menus.',
        'suggestions' => ['do you have vegetarian restaurants', 'do you have vegan options', 'how do i find restaurants with specific dietary options']
    ],
    [
        'question' => 'what cuisines are available',
        'answer' => 'We offer a wide range of cuisines including Italian, Mexican, Chinese, Indian, Thai, Japanese, American, Mediterranean, and more. You can browse restaurants by cuisine type to find what you\'re looking for.',
        'suggestions' => ['what types of restaurants do you have', 'how do i find restaurants by cuisine', 'do you have international restaurants']
    ],
    [
        'question' => 'how do i find restaurants near me',
        'answer' => 'You can find restaurants near you by using the location filter on our homepage. Enter your address or allow us to use your current location, and we\'ll show you restaurants in your area.',
        'suggestions' => ['what types of restaurants do you have', 'how do i filter restaurants by rating', 'can i save my favorite restaurants']
    ],
    [
        'question' => 'how do i find restaurants with specific dietary options',
        'answer' => 'You can filter restaurants by dietary preferences such as vegetarian, vegan, gluten-free, and more. Use the filter options on our homepage to find restaurants that meet your dietary requirements.',
        'suggestions' => ['do you have vegetarian restaurants', 'do you have vegan options', 'do you have gluten-free options']
    ],
    [
        'question' => 'how do i find restaurants by cuisine',
        'answer' => 'You can browse restaurants by cuisine type using the filter options on our homepage. Select your preferred cuisine, and we\'ll show you restaurants that specialize in that type of food.',
        'suggestions' => ['what cuisines are available', 'how do i find restaurants near me', 'how do i filter restaurants by rating']
    ],
    [
        'question' => 'do you have international restaurants',
        'answer' => 'Yes, we have many international restaurants offering cuisines from around the world. You can browse restaurants by cuisine type to find international options.',
        'suggestions' => ['what cuisines are available', 'how do i find restaurants by cuisine', 'what types of restaurants do you have']
    ],
    [
        'question' => 'how do i filter restaurants by rating',
        'answer' => 'You can filter restaurants by rating using the filter options on our homepage. Select your preferred minimum rating (e.g., 4 stars and above), and we\'ll show you restaurants that meet that criteria.',
        'suggestions' => ['how do restaurant ratings work', 'how do i find restaurants near me', 'can i save my favorite restaurants']
    ],
    [
        'question' => 'can i save my favorite restaurants',
        'answer' => 'Yes, you can save your favorite restaurants by clicking the heart icon on their page. You can view your saved restaurants by going to "My Favorites" in your account dashboard.',
        'suggestions' => ['how do i find restaurants near me', 'how do i filter restaurants by rating', 'can i get notifications from my favorite restaurants']
    ],
    [
        'question' => 'can i get notifications from my favorite restaurants',
        'answer' => 'Yes, you can enable notifications for your favorite restaurants to receive updates about new menu items, special offers, and promotions. Go to your account settings and select "Notification Preferences" to manage these settings.',
        'suggestions' => ['can i save my favorite restaurants', 'how do i find restaurants near me', 'do you offer any discounts']
    ],
    [
        'question' => 'how do i view a restaurant\'s menu',
        'answer' => 'To view a restaurant\'s menu, go to their page and click on the "Menu" tab. You can browse through different categories and see details, prices, and photos of each dish.',
        'suggestions' => ['can i see nutritional information for menu items', 'how do i find specific dishes', 'can i filter menu items by dietary preferences']
    ],
    [
        'question' => 'can i see nutritional information for menu items',
        'answer' => 'Some restaurants provide nutritional information for their menu items. Look for the nutritional info icon next to menu items, or check the restaurant\'s page for a link to their complete nutritional information.',
        'suggestions' => ['how do i view a restaurant\'s menu', 'how do i find specific dishes', 'can i filter menu items by dietary preferences']
    ],
    [
        'question' => 'how do i find specific dishes',
        'answer' => 'You can search for specific dishes using the search bar on our homepage. Enter the name of the dish you\'re looking for, and we\'ll show you restaurants that offer it.',
        'suggestions' => ['how do i view a restaurant\'s menu', 'can i see nutritional information for menu items', 'can i filter menu items by dietary preferences']
    ],
    [
        'question' => 'can i filter menu items by dietary preferences',
        'answer' => 'Yes, you can filter menu items by dietary preferences such as vegetarian, vegan, gluten-free, and more. Use the filter options on a restaurant\'s menu page to find items that meet your dietary requirements.',
        'suggestions' => ['how do i view a restaurant\'s menu', 'can i see nutritional information for menu items', 'how do i find specific dishes']
    ],
    [
        'question' => 'do you have any local specialties',
        'answer' => 'Yes, we feature many local specialties from restaurants in your area. You can find these by browsing the "Local Favorites" section on our homepage or by filtering restaurants by cuisine type.',
        'suggestions' => ['what types of restaurants do you have', 'how do i find restaurants near me', 'what cuisines are available']
    ],
    [
        'question' => 'do you have any seasonal menu items',
        'answer' => 'Yes, many restaurants on our platform offer seasonal menu items. These are typically highlighted on the restaurant\'s page or in the "Special Offers" section. You can also filter restaurants by "Seasonal Specials" to find these items.',
        'suggestions' => ['how do i view a restaurant\'s menu', 'how do i find specific dishes', 'do you have any local specialties']
    ],
    [
        'question' => 'do you have any healthy food options',
        'answer' => 'Yes, we have many restaurants that offer healthy food options. You can filter restaurants by dietary preferences such as "Healthy," "Low-Calorie," or "Organic" to find these options.',
        'suggestions' => ['how do i find restaurants with specific dietary options', 'can i see nutritional information for menu items', 'do you have vegetarian restaurants']
    ],
    [
        'question' => 'do you have any kid-friendly restaurants',
        'answer' => 'Yes, we have many kid-friendly restaurants. You can filter restaurants by "Kid-Friendly" to find options that offer children\'s menus, high chairs, and other amenities for families.',
        'suggestions' => ['what types of restaurants do you have', 'how do i find restaurants near me', 'do you have any family restaurants']
    ],
    [
        'question' => 'do you have any family restaurants',
        'answer' => 'Yes, we have many family restaurants that offer a welcoming atmosphere and menu options for all ages. You can filter restaurants by "Family-Friendly" to find these options.',
        'suggestions' => ['do you have any kid-friendly restaurants', 'what types of restaurants do you have', 'how do i find restaurants near me']
    ],
    [
        'question' => 'do you have any romantic restaurants',
        'answer' => 'Yes, we have many romantic restaurants perfect for date nights or special occasions. You can filter restaurants by "Romantic" to find options with a cozy atmosphere and suitable menu.',
        'suggestions' => ['what types of restaurants do you have', 'how do i find restaurants by cuisine', 'do you have any fine dining restaurants']
    ],
    [
        'question' => 'do you have any fine dining restaurants',
        'answer' => 'Yes, we have many fine dining restaurants offering an upscale dining experience. You can filter restaurants by "Fine Dining" to find these options.',
        'suggestions' => ['do you have any romantic restaurants', 'what types of restaurants do you have', 'how do i find restaurants by cuisine']
    ],
    [
        'question' => 'do you have any casual dining restaurants',
        'answer' => 'Yes, we have many casual dining restaurants offering a relaxed atmosphere and diverse menu options. You can filter restaurants by "Casual Dining" to find these options.',
        'suggestions' => ['what types of restaurants do you have', 'how do i find restaurants near me', 'do you have any family restaurants']
    ],
    [
        'question' => 'do you have any cafes',
        'answer' => 'Yes, we have many cafes offering coffee, tea, pastries, and light meals. You can filter restaurants by "Cafe" to find these options.',
        'suggestions' => ['what types of restaurants do you have', 'how do i find cafes near me', 'do you have any breakfast restaurants']
    ],
    [
        'question' => 'how do i find cafes near me',
        'answer' => 'You can find cafes near you by using the location filter on our homepage and selecting "Cafe" as the restaurant type. Enter your address or allow us to use your current location, and we\'ll show you cafes in your area.',
        'suggestions' => ['do you have any cafes', 'how do i find restaurants near me', 'do you have any breakfast restaurants']
    ],
    [
        'question' => 'do you have any breakfast restaurants',
        'answer' => 'Yes, we have many restaurants that serve breakfast. You can filter restaurants by "Breakfast" to find these options.',
        'suggestions' => ['do you have any cafes', 'how do i find restaurants near me', 'what types of restaurants do you have']
    ],
    [
        'question' => 'do you have any dessert places',
        'answer' => 'Yes, we have many dessert places offering ice cream, cakes, pastries, and other sweet treats. You can filter restaurants by "Dessert" to find these options.',
        'suggestions' => ['what types of restaurants do you have', 'how do i find restaurants near me', 'do you have any cafes']
    ],
    [
        'question' => 'do you have any food trucks',
        'answer' => 'Yes, we feature many food trucks on our platform. You can filter restaurants by "Food Truck" to find these options. Food trucks often have unique menus and can be found at various locations.',
        'suggestions' => ['how do i find food trucks near me', 'what types of restaurants do you have', 'how do i track a food truck\'s location']
    ],
    [
        'question' => 'how do i find food trucks near me',
        'answer' => 'You can find food trucks near you by using the location filter on our homepage and selecting "Food Truck" as the restaurant type. Enter your address or allow us to use your current location, and we\'ll show you food trucks in your area.',
        'suggestions' => ['do you have any food trucks', 'how do i track a food truck\'s location', 'how do i find restaurants near me']
    ],
    [
        'question' => 'how do i track a food truck\'s location',
        'answer' => 'Some food trucks on our platform offer real-time location tracking. If available, you\'ll see a "Track Location" button on the food truck\'s page. Click on it to see their current location on a map.',
        'suggestions' => ['do you have any food trucks', 'how do i find food trucks near me', 'how do i place an order from a food truck']
    ],
    [
        'question' => 'how do i place an order from a food truck',
        'answer' => 'You can place an order from a food truck just like any other restaurant on our platform. Go to the food truck\'s page, browse their menu, add items to your cart, and proceed to checkout. Some food trucks may have specific pickup locations or delivery areas.',
        'suggestions' => ['do you have any food trucks', 'how do i find food trucks near me', 'how do i track a food truck\'s location']
    ],
];

// Combine all Q&A pairs
$additional_qa_pairs = array_merge($food_ordering_qa, $restaurant_food_qa);

// Return the combined array
return $additional_qa_pairs;
?>