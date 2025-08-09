<?php
// Dynamically determine the base URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = pathinfo($script_name, PATHINFO_DIRNAME);
$base_url = $protocol . '://' . $host . $base_path;
// Remove '/garage' from the end if present since we're already in that directory
$base_url = rtrim($base_url, '/');
?>
<!DOCTYPE html>
<html lang="en">"// Common paths for all pages
<!-- $base_url = "http://localhost/garage"; -->
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Car Garage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3a6ea5;
            --primary-dark: #004e92;
            --primary-light: #6a93cb;
            --secondary-color: #ff6b6b;
            --secondary-dark: #c83349;
            --accent-color: #feca57;
            --dark-bg: #1e272e;
            --darker-bg: #121a21;
            --card-bg: #2d3436;
            --light-text: #f0f0f0;
            --gray-text: #a4b0be;
            --success-color: #26de81;
            --error-color: #fc5c65;
            --warning-color: #fed330;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--dark-bg);
            color: var(--light-text);
            line-height: 1.6;
            background-image: linear-gradient(135deg, rgba(0, 78, 146, 0.1) 0%, rgba(106, 147, 203, 0.1) 100%);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: var(--darker-bg);
            padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo i {
            font-size: 38px;
            margin-right: 12px;
            color: var(--primary-light);
            text-shadow: 0 0 10px rgba(106, 147, 203, 0.5);
        }
        
        .logo h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--light-text);
            letter-spacing: 0.5px;
        }
        
        .contact-info {
            display: flex;
            gap: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .contact-item i {
            color: var(--primary-light);
            font-size: 18px;
        }
        
        .contact-item span {
            font-size: 14px;
            color: var(--gray-text);
        }
        
        .welcome-text {
            padding: 20px 0;
            text-align: center;
        }
        
        .welcome-text h2 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--light-text);
        }
        
        .welcome-text p {
            font-size: 16px;
            color: var(--gray-text);
            max-width: 800px;
            margin: 0 auto;
        }
        
        nav {
            display: flex;
            justify-content: center;
            padding: 15px 0;
        }
        
        nav a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: rgba(30, 39, 46, 0.8);
            color: var(--light-text);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(106, 147, 203, 0.3);
        }
        
        nav a:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-color: var(--primary-light);
        }
        
        nav a.active {
            background-color: var(--primary-color);
            border-color: var(--primary-light);
        }
        
        /* Section Title */
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-title h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--light-text);
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--primary-light);
        }
        
        .section-title p {
            color: var(--gray-text);
            font-size: 16px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Common form styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--light-text);
        }
        
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #2d3436;
            border-radius: 6px;
            font-size: 16px;
            background-color: #2d3436;
            color: var(--light-text);
            transition: all 0.3s ease;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #219653;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #718093;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #535c68;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #e67e22;
        }
        
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        
        .success {
            background-color: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #27ae60;
        }
        
        .error {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #c0392b;
        }
        
        /* Card styles */
        .card {
            background-color: var(--darker-bg);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: var(--darker-bg);
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #2d3436;
        }
        
        thead th {
            background-color: var(--darkest-bg);
            color: var(--light-text);
            font-weight: 600;
        }
        
        tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        .status-pending {
            color: var(--warning-color);
            font-weight: 500;
        }
        
        .status-completed {
            color: var(--success-color);
            font-weight: 500;
        }
        
        .status-cancelled {
            color: var(--accent-color);
            font-weight: 500;
        }
        
        /* Why Choose Us Section */
        .why-us-section {
            padding: 50px 0;
            background-color: rgba(30, 39, 46, 0.5);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .feature-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            padding: 30px;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            background-color: rgba(106, 147, 203, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .feature-icon i {
            font-size: 30px;
            color: var(--primary-light);
        }
        
        .feature-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--light-text);
        }
        
        .feature-description {
            color: var(--gray-text);
            font-size: 15px;
            line-height: 1.6;
        }
        
        /* Footer */
        footer {
            background-color: var(--darker-bg);
            padding-top: 60px;
            margin-top: 50px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            padding-bottom: 40px;
        }
        
        .footer-col h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--light-text);
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-light);
        }
        
        .footer-col p {
            color: var(--gray-text);
            margin-bottom: 15px;
            line-height: 1.7;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: var(--gray-text);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .footer-links a i {
            color: var(--primary-light);
            font-size: 14px;
        }
        
        .footer-links a:hover {
            color: var(--light-text);
            transform: translateX(5px);
        }
        
        .contact-info p {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .contact-info i {
            color: var(--primary-light);
            font-size: 16px;
            margin-top: 5px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: rgba(106, 147, 203, 0.1);
            color: var(--light-text);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 20px 0;
            text-align: center;
        }
        
        .footer-bottom p {
            color: var(--gray-text);
            font-size: 14px;
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
            
            .hero {
                height: 350px;
            }
            
            .hero h2 {
                font-size: 32px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .hero-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .feature-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <i class="fas fa-car-alt"></i>
                    <h1>Premium Car Garage</h1>
                </div>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+1 (555) 123-4567</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contact@premiumgarage.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Mon-Fri: 8:00 AM - 6:00 PM</span>
                    </div>
                </div>
            </div>
            
            <nav>
                <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Book Service</a>
                <a href="service_history.php" <?php echo basename($_SERVER['PHP_SELF']) == 'service_history.php' ? 'class="active"' : ''; ?>>Service History</a>
                <a href="admin.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'class="active"' : ''; ?>>Admin Panel</a>
            </nav>
        </div>
    </header>
    
    <div class="content-wrapper">
        <div class="container">
