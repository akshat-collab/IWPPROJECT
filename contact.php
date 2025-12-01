<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:8000/');
}
require_once 'config/config.php';
require_once 'config/database.php';

$conn = getDBConnection();
$contact = $conn->query("SELECT * FROM contact_info WHERE id=1")->fetch_assoc();
closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="page-header">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Get in touch with us</p>
        </div>
    </section>
    
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Contact Information</h2>
                    
                    <?php if ($contact['address']): ?>
                        <div class="contact-item">
                            <div class="contact-icon">📍</div>
                            <div>
                                <h3>Address</h3>
                                <p><?php echo nl2br(htmlspecialchars($contact['address'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contact['phone']): ?>
                        <div class="contact-item">
                            <div class="contact-icon">📞</div>
                            <div>
                                <h3>Phone</h3>
                                <p><a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>"><?php echo htmlspecialchars($contact['phone']); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contact['email']): ?>
                        <div class="contact-item">
                            <div class="contact-icon">✉️</div>
                            <div>
                                <h3>Email</h3>
                                <p><a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>"><?php echo htmlspecialchars($contact['email']); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contact['whatsapp']): ?>
                        <div class="contact-item">
                            <div class="contact-icon">💬</div>
                            <div>
                                <h3>WhatsApp</h3>
                                <p><a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $contact['whatsapp']); ?>" target="_blank"><?php echo htmlspecialchars($contact['whatsapp']); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="social-links-section">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <?php if ($contact['facebook']): ?>
                                <a href="<?php echo htmlspecialchars($contact['facebook']); ?>" target="_blank" class="social-link">Facebook</a>
                            <?php endif; ?>
                            <?php if ($contact['instagram']): ?>
                                <a href="<?php echo htmlspecialchars($contact['instagram']); ?>" target="_blank" class="social-link">Instagram</a>
                            <?php endif; ?>
                            <?php if ($contact['twitter']): ?>
                                <a href="<?php echo htmlspecialchars($contact['twitter']); ?>" target="_blank" class="social-link">Twitter</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-section">
                    <h2>Send us a Message</h2>
                    <form id="contactForm" class="contact-form">
                        <div class="form-group">
                            <label for="contact_name">Your Name *</label>
                            <input type="text" id="contact_name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_email">Your Email *</label>
                            <input type="email" id="contact_email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_subject">Subject *</label>
                            <input type="text" id="contact_subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_message">Message *</label>
                            <textarea id="contact_message" name="message" rows="6" required></textarea>
                        </div>
                        
                        <div id="contact-form-message" class="form-message"></div>
                        
                        <button type="submit" class="btn btn-primary btn-large">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script>
        // Contact Form AJAX Submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitButton = form.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('contact-form-message');
            
            // Basic validation
            const name = document.getElementById('contact_name').value.trim();
            const email = document.getElementById('contact_email').value.trim();
            const subject = document.getElementById('contact_subject').value.trim();
            const message = document.getElementById('contact_message').value.trim();
            
            if (!name || !email || !subject || !message) {
                showMessage(messageDiv, 'Please fill in all required fields', 'error');
                return;
            }
            
            if (!validateEmail(email)) {
                showMessage(messageDiv, 'Please enter a valid email address', 'error');
                return;
            }
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';
            
            // Prepare form data
            const formData = new FormData(form);
            
            // AJAX submission
            fetch('api/contact-submit.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(messageDiv, data.message, 'success');
                    form.reset();
                } else {
                    showMessage(messageDiv, data.message || 'Error sending message. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(messageDiv, 'Network error. Please check your connection and try again.', 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Send Message';
            });
        });
        
        function showMessage(element, message, type) {
            if (!element) return;
            
            element.textContent = message;
            element.className = 'form-message ' + type;
            element.style.display = 'block';
            
            if (type === 'success') {
                setTimeout(() => {
                    element.style.display = 'none';
                }, 5000);
            }
        }
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>
</body>
</html>

