        </div>
    </div>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <h4>About Us</h4>
                    <p>Premium Car Garage has been providing exceptional automotive service since 2005. Our team of certified mechanics are committed to quality workmanship and customer satisfaction.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Our Services</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Engine Diagnostics</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Oil Changes</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Brake Repairs</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Transmission Service</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Wheel Alignment</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Air Conditioning</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Service Centers</h4>
                    <div class="contact-info">
                        <p>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>
                                <strong>Downtown Center</strong><br>
                                123 Main Street, New York, NY 10001<br>
                                Phone: (212) 555-1234
                            </span>
                        </p>
                        <p>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>
                                <strong>Uptown Center</strong><br>
                                456 Fifth Avenue, New York, NY 10022<br>
                                Phone: (212) 555-5678
                            </span>
                        </p>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Contact Us</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-phone"></i> <span>+1 (555) 123-4567</span></p>
                        <p><i class="fas fa-envelope"></i> <span>contact@premiumgarage.com</span></p>
                        <p><i class="fas fa-clock"></i> <span>Mon-Fri: 8:00 AM - 6:00 PM</span></p>
                        <p><i class="fas fa-clock"></i> <span>Sat: 9:00 AM - 4:00 PM</span></p>
                        <p><i class="fas fa-clock"></i> <span>Sun: Closed</span></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> Premium Car Garage. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
