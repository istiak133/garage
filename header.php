<?php
// Common paths for all pages
$base_url = "http://localhost/garage";
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
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-bg: #1a1a2e;
            --darker-bg: #16213e;
            --darkest-bg: #0f3460;
            --light-text: #f5f5f5;
            --gray-text: #b2bec3;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
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
        
        .logo img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        
        .logo h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
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
            color: var(--primary-color);
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
            background-color: var(--darkest-bg);
            color: var(--light-text);
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        nav a:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        nav a.active {
            background-color: var(--primary-color);
        }
        
        /* Content wrapper */
        .content-wrapper {
            padding: 40px 0;
            min-height: calc(100vh - 400px); /* Adjust based on header/footer height */
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
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <i class="fas fa-cogs" style="font-size: 36px; color: var(--primary-color);"></i>
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
                </div>
            </div>
            
            <div class="welcome-text">
                <h2>Expert Auto Care & Repair</h2>
                <p>Welcome to Premium Car Garage, where experienced mechanics deliver top-quality service for all your automotive needs. With advanced equipment and personalized care, we ensure your vehicle performs at its best.</p>
            </div>
            
            <nav>
                <a href="<?php echo $base_url; ?>/index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Book Appointment</a>
                <a href="<?php echo $base_url; ?>/service_history.php" <?php echo basename($_SERVER['PHP_SELF']) == 'service_history.php' ? 'class="active"' : ''; ?>>Service History</a>
                <a href="<?php echo $base_url; ?>/admin.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'class="active"' : ''; ?>>Admin Panel</a>
            </nav>
        </div>
    </header>
    
    <div class="content-wrapper">
        <div class="container">
