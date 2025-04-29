<?php
// Start output buffering
ob_start();

require_once 'includes/session.php';
require_once 'includes/db.php';

// Include header
require_once 'includes/header.php';
?>

<!-- Add Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Simple Hero Section -->
<div class="about-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="hero-content">
                    <!-- <img src="assets/images/logo.png" alt="LocalCarving Logo" class="about-logo mb-4"> -->
                    <h1 class="display-4 fw-bold text-white mb-3">About LocalCarving</h1>
                    <p class="lead text-white-50">Connecting food lovers with the best local restaurants since 2020</p>
                </div>
            </div>
            <div class="col-md-6">
                <img src="assets\images\hero_food.jpg" alt="LocalCarving Hero" class="img-fluid rounded shadow small-hero-img">
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <!-- About Us Section -->
            <section class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Our Story</h2>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p>LocalCarving was founded in 2020 with a simple mission: to connect food lovers with the best local restaurants in their area. What started as a small platform has grown into a comprehensive food delivery and restaurant discovery service.</p>
                                <p>Our founders recognized that while there were many food delivery apps available, none were truly focused on supporting local restaurants and providing a personalized experience for users. LocalCarving was created to fill this gap.</p>
                                <p>Today, LocalCarving serves thousands of users across multiple cities, helping them discover new restaurants, place orders, and enjoy their favorite local cuisine from the comfort of their homes.</p>
                            </div>
                            <div class="col-md-6">
                                <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="LocalCarving Team" class="img-fluid rounded shadow-sm small-img">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Mission and Values Section -->
            <section class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Our Mission & Values</h2>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="value-card h-100 p-3">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-utensils fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="h5 text-center">Supporting Local Businesses</h3>
                                    <p class="card-text">We believe in the power of local restaurants to bring communities together. Our platform is designed to help these businesses thrive in the digital age.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="value-card h-100 p-3">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-heart fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="h5 text-center">User-Centric Experience</h3>
                                    <p class="card-text">We put our users first, creating an intuitive and enjoyable platform that makes food discovery and ordering seamless and fun.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="value-card h-100 p-3">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-leaf fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="h5 text-center">Sustainability</h3>
                                    <p class="card-text">We're committed to reducing our environmental impact through eco-friendly packaging options and optimizing delivery routes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Features Section -->
            <section class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Our Features</h2>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="feature-card h-100 p-3">
                                    <h3 class="h5">Restaurant Discovery</h3>
                                    <p class="card-text">Our advanced search and filtering system helps users find restaurants based on cuisine type, dietary preferences, rating, and location. Users can browse through detailed restaurant profiles with photos, menus, and reviews.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Advanced filtering by cuisine, dietary preferences, and rating</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Detailed restaurant profiles with photos and menus</li>
                                        <li><i class="fas fa-check text-success me-2"></i>User reviews and ratings system</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Favorite restaurant saving</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="feature-card h-100 p-3">
                                    <h3 class="h5">Ordering System</h3>
                                    <p class="card-text">Our intuitive ordering system makes it easy for users to place and track orders. Users can customize their orders, schedule future deliveries, and choose from multiple payment options.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Easy-to-use ordering interface</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Order customization with special instructions</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Real-time order tracking</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Multiple payment methods</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Scheduled ordering for future delivery</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="feature-card h-100 p-3">
                                    <h3 class="h5">Restaurant Management</h3>
                                    <p class="card-text">We provide restaurant owners with powerful tools to manage their online presence, process orders, and engage with customers. Our platform helps restaurants streamline their operations and increase their reach.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Menu management system</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Order processing dashboard</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Customer communication tools</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Analytics and reporting</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Promotion and discount management</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="feature-card h-100 p-3">
                                    <h3 class="h5">User Experience</h3>
                                    <p class="card-text">We've designed our platform with user experience in mind, creating a seamless and enjoyable journey from restaurant discovery to order completion.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Responsive design for all devices</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Personalized recommendations</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Loyalty program with points and rewards</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Intelligent chatbot for customer support</li>
                                        <li><i class="fas fa-check text-success me-2"></i>User-friendly account management</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Technology Stack Section -->
            <section class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Our Technology</h2>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p>LocalCarving is built using cutting-edge technologies to ensure a fast, secure, and reliable platform for our users and restaurant partners.</p>
                                <p>Our technology stack includes:</p>
                                <ul class="list-unstyled">
                                    <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript, Bootstrap, jQuery</li>
                                    <li><strong>Backend:</strong> PHP, MySQL, Apache</li>
                                    <li><strong>APIs:</strong> Payment gateways, Maps integration, SMS notifications</li>
                                    <li><strong>AI Features:</strong> Intelligent chatbot, personalized recommendations</li>
                                    <li><strong>Security:</strong> SSL encryption, secure payment processing, data protection</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Technology Stack" class="img-fluid rounded shadow-sm small-img">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Developer Team Section -->
            <section class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Our Development Team</h2>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="developer-card h-100">
                                    <div class="developer-img-container">
                                        <img src="team\sanuj1.jpg" class="developer-img" alt="Developer 1">
                                    </div>
                                    <div class="text-center mt-3">
                                        <h3 class="h5"> Sanuj Tiwari</h3>
                                        <p class="text-muted mb-2 small">Lead Developer</p>
                                         <div class="social-links">
                                            <a href="#" class="social-link" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                                            <a href="#" class="social-link" title="GitHub"><i class="fab fa-github"></i></a>
                                            <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="developer-card h-100">
                                    <div class="developer-img-container">
                                        <img src="team\sumit.jpg" class="developer-img" alt="Developer 2">
                                    </div>
                                    <div class="text-center mt-3">
                                        <h3 class="h5"> Sumit Kumar</h3>
                                        <p class="text-muted mb-2 small">Frontend Developer</p>
                                         <div class="social-links">
                                            <a href="#" class="social-link" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                                            <a href="#" class="social-link" title="GitHub"><i class="fab fa-github"></i></a>
                                            <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="developer-card h-100">
                                    <div class="developer-img-container">
                                        <img src="team\vishu.jpg" class="developer-img" alt="Developer 3">
                                    </div>
                                    <div class="text-center mt-3">
                                        <h3 class="h5"> Vishwadip Chaudhary</h3>
                                        <p class="text-muted mb-2 small">Backend Developer</p>
                                         <div class="social-links">
                                            <a href="#" class="social-link" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                                            <a href="#" class="social-link" title="GitHub"><i class="fab fa-github"></i></a>
                                            <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Contact Section -->
            <section class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Get in Touch</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Have questions or feedback about LocalCarving? We'd love to hear from you!</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-envelope text-primary me-2"></i> contact@localcarving.com</li>
                                    <li><i class="fas fa-phone text-primary me-2"></i> +1 (555) 123-4567</li>
                                    <li><i class="fas fa-map-marker-alt text-primary me-2"></i> 123 Main Street, City, Country</li>
                                </ul>
                                <div class="mt-3">
                                    <a href="#" class="text-primary me-3"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="text-primary me-3"><i class="fab fa-twitter"></i></a>
                                    <a href="#" class="text-primary me-3"><i class="fab fa-instagram"></i></a>
                                    <a href="#" class="text-primary"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <form>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    /* Hero Section */
    .about-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        padding: 60px 0;
        margin-bottom: 30px;
    }
    
    .about-logo {
        max-width: 180px;
        filter: brightness(0) invert(1);
    }
    
    /* Small Images */
    .small-img {
        max-width: 80%;
        margin: 0 auto;
        display: block;
    }
    
    .small-hero-img {
        max-width: 90%;
        margin: 0 auto;
        display: block;
    }
    
    /* Developer Images */
    .developer-img-container {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #0d6efd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .developer-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }
    
    .developer-img-container:hover .developer-img {
        transform: scale(1.05);
    }
    
    /* Social Links */
    .social-links {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 15px;
    }
    
    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background-color: #e9ecef;
        color: #0d6efd;
        border-radius: 50%;
        transition: all 0.3s ease;
        font-size: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .social-link:hover {
        background-color: #0d6efd;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Cards */
    .card {
        border: none;
        transition: transform 0.3s;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .about-hero {
            padding: 40px 0;
        }
        
        .hero-content {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .about-logo {
            margin: 0 auto;
        }
        
        .small-img, .small-hero-img {
            max-width: 100%;
        }
        
        .developer-img-container {
            width: 120px;
            height: 120px;
        }
    }
</style>

<?php
// Include footer
require_once 'includes/footer.php';

// Flush the output buffer
ob_end_flush();
?>