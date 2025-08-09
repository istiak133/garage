        </div>
    </div>
    
    <footer>
        <div class="brands-section">
            <div class="container">
                <h3>Trusted by Top Automotive Brands</h3>
                <div class="brand-logos">
                    <div class="brand-logo">
                        <i class="fab fa-mercedes-benz"></i>
                        <span>Mercedes-Benz</span>
                    </div>
                    <div class="brand-logo">
                        <i class="fab fa-bmw"></i>
                        <span>BMW</span>
                    </div>
                    <div class="brand-logo">
                        <i class="fab fa-audi"></i>
                        <span>Audi</span>
                    </div>
                    <div class="brand-logo">
                        <i class="fab fa-tesla"></i>
                        <span>Tesla</span>
                    </div>
                    <div class="brand-logo">
                        <i class="fab fa-ford"></i>
                        <span>Ford</span>
                    </div>
                    <div class="brand-logo">
                        <i class="fab fa-toyota"></i>
                        <span>Toyota</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-main">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-col">
                        <h4>About Us</h4>
                        <p>Premium Car Garage has been providing exceptional automotive service since 2005. Our team of certified mechanics are committed to quality workmanship and customer satisfaction.</p>
                    </div>
                    
                    <div class="footer-col">
                        <h4>Service Centers</h4>
                        <ul>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Downtown Center</strong>
                                    <p>123 Main Street, New York, NY 10001</p>
                                    <p>Phone: (212) 555-1234</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Uptown Center</strong>
                                    <p>456 Fifth Avenue, New York, NY 10022</p>
                                    <p>Phone: (212) 555-5678</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="footer-col">
                        <h4>Contact Us</h4>
                        <div class="contact-info">
                            <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                            <p><i class="fas fa-envelope"></i> contact@premiumgarage.com</p>
                            <p><i class="fas fa-clock"></i> Mon-Fri: 8:00 AM - 6:00 PM</p>
                            <p><i class="fas fa-clock"></i> Sat: 9:00 AM - 4:00 PM</p>
                            <p><i class="fas fa-clock"></i> Sun: Closed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> Premium Car Garage. All Rights Reserved.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <style>
        /* Footer Styles */
        footer {
            background-color: var(--darker-bg);
            color: var(--gray-text);
        }
        
        .brands-section {
            background-color: var(--darkest-bg);
            padding: 40px 0;
            text-align: center;
        }
        
        .brands-section h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--light-text);
        }
        
        .brand-logos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
        }
        
        .brand-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .brand-logo i {
            font-size: 36px;
            color: var(--primary-color);
        }
        
        .brand-logo span {
            font-size: 14px;
            color: var(--gray-text);
        }
        
        .footer-main {
            background-color: var(--darker-bg);
            padding: 60px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
        }
        
        .footer-col {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-col h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--light-text);
        }
        
        .footer-col p {
            margin-bottom: 15px;
            line-height: 1.7;
        }
        
        .footer-col ul {
            list-style: none;
        }
        
        .footer-col ul li {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .footer-col ul li i {
            color: var(--primary-color);
            font-size: 18px;
            margin-top: 5px;
        }
        
        .footer-col ul li strong {
            display: block;
            color: var(--light-text);
            margin-bottom: 5px;
        }
        
        .contact-info p {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .contact-info i {
            color: var(--primary-color);
            font-size: 16px;
            width: 20px;
        }
        
        .footer-bottom {
            background-color: var(--dark-bg);
            padding: 20px 0;
            text-align: center;
        }
        
        .footer-bottom .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: var(--darker-bg);
            color: var(--light-text);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .contact-info {
                flex-direction: column;
                gap: 10px;
            }
            
            .footer-content {
                flex-direction: column;
            }
            
            .footer-bottom .container {
                flex-direction: column;
                gap: 15px;
            }
            
            .brand-logos {
                gap: 20px;
            }
            
            .brand-logo i {
                font-size: 30px;
            }
        }
    </style>
</body>
</html>
