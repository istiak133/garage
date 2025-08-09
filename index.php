<?php
include 'config.php';

// Get all mechanics for the dropdown
$mechanics_query = "SELECT * FROM mechanics ORDER BY name";
$mechanics_result = $conn->query($mechanics_query);

// Handle form submission
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'book_appointment') {
    $client_name = $_POST['client_name'] ?? '';
    $client_address = $_POST['client_address'] ?? '';
    $client_phone = $_POST['client_phone'] ?? '';
    $car_license = $_POST['car_license'] ?? '';
    $car_engine = $_POST['car_engine'] ?? '';
    $car_model = $_POST['car_model'] ?? '';
    $car_make = $_POST['car_make'] ?? '';
    $service_type = $_POST['service_type'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $mechanic_id = $_POST['mechanic_id'] ?? '';
    
    // Validation
    if (empty($client_name) || empty($client_address) || empty($client_phone) || 
        empty($car_license) || empty($car_engine) || empty($car_model) || empty($car_make) ||
        empty($service_type) || empty($appointment_date) || empty($appointment_time) || empty($mechanic_id)) {
        $error = "Please fill in all fields";
    } else {
        // Check if client already has appointment on that date
        $check_client = "SELECT * FROM appointments WHERE client_phone = ? AND appointment_date = ? AND status = 'pending'";
        $stmt = $conn->prepare($check_client);
        $stmt->bind_param("ss", $client_phone, $appointment_date);
        $stmt->execute();
        $client_result = $stmt->get_result();
        
        if ($client_result->num_rows > 0) {
            $error = "You already have an appointment on this date!";
        } else {
            // Check if mechanic is available (max 4 appointments per day)
            $check_mechanic = "SELECT COUNT(*) as count FROM appointments WHERE mechanic_id = ? AND appointment_date = ? AND status = 'pending'";
            $stmt = $conn->prepare($check_mechanic);
            $stmt->bind_param("is", $mechanic_id, $appointment_date);
            $stmt->execute();
            $mechanic_result = $stmt->get_result();
            $mechanic_count = $mechanic_result->fetch_assoc()['count'];
            
            if ($mechanic_count >= 4) {
                // Find next available date for this mechanic
                $next_date_query = "
                    SELECT DATE_ADD(?, INTERVAL seq.seq DAY) as next_date
                    FROM (
                        SELECT 1 as seq UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 
                        UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
                    ) seq
                    LEFT JOIN (
                        SELECT appointment_date, COUNT(*) as count 
                        FROM appointments 
                        WHERE mechanic_id = ? AND status = 'pending' 
                        GROUP BY appointment_date
                    ) booked ON DATE_ADD(?, INTERVAL seq.seq DAY) = booked.appointment_date
                    WHERE (booked.count IS NULL OR booked.count < 4) 
                    ORDER BY seq.seq LIMIT 1
                ";
                $stmt = $conn->prepare($next_date_query);
                $stmt->bind_param("sis", $appointment_date, $mechanic_id, $appointment_date);
                $stmt->execute();
                $next_date_result = $stmt->get_result();
                
                if ($next_date_result->num_rows > 0) {
                    $next_available = $next_date_result->fetch_assoc()['next_date'];
                    
                    $mechanic_name_query = "SELECT name FROM mechanics WHERE id = ?";
                    $stmt = $conn->prepare($mechanic_name_query);
                    $stmt->bind_param("i", $mechanic_id);
                    $stmt->execute();
                    $mechanic_name_result = $stmt->get_result();
                    $mechanic_name = $mechanic_name_result->fetch_assoc()['name'];
                    
                    $error = "Selected mechanic ($mechanic_name) is fully booked on $appointment_date. Next available date: $next_available";
                } else {
                    $error = "Selected mechanic is fully booked for the next 10 days. Please choose another mechanic.";
                }
            } else {
                // Book the appointment - using prepared statement to prevent SQL injection
                $insert_query = "INSERT INTO appointments (client_name, client_address, client_phone, car_license, car_engine, car_model, car_make, service_type, appointment_date, appointment_time, mechanic_id) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ssssssssssi", $client_name, $client_address, $client_phone, $car_license, $car_engine, $car_model, $car_make, $service_type, $appointment_date, $appointment_time, $mechanic_id);
                
                if ($stmt->execute()) {
                    $message = "Appointment booked successfully!";
                } else {
                    $error = "Error booking appointment: " . $conn->error;
                }
            }
        }
    }
}

// Get car makes for dropdown
$car_makes = [
    "Audi", "BMW", "Chevrolet", "Dodge", "Ford", "Honda", "Hyundai", "Jeep", "Kia", 
    "Lexus", "Mazda", "Mercedes-Benz", "Nissan", "Subaru", "Tesla", "Toyota", "Volkswagen", "Volvo"
];

// Service types
$service_types = [
    "Regular Maintenance", "Oil Change", "Brake Service", "Engine Repair", 
    "Transmission Service", "Battery Replacement", "AC Service", "Wheel Alignment",
    "Tire Replacement", "Electrical System Repair", "Diagnostic Check", "Full Service"
];

// Available time slots
$time_slots = [
    "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Car Garage - Book Your Service</title>
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
        
        /* Hero Section */
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1625047509255-37d3126db781?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            align-items: center;
            position: relative;
            margin-bottom: 50px;
        }
        
        .hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .hero h2 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero p {
            font-size: 18px;
            color: #e0e0e0;
            margin-bottom: 30px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .hero-btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .primary-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .primary-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .secondary-btn {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .secondary-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Appointment Form */
        .appointment-section {
            padding: 50px 0;
        }
        
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
        
        .appointment-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 50px;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 30px;
        }
        
        .card-header h3 {
            font-size: 22px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
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
            border: 1px solid #3d4852;
            border-radius: 6px;
            font-size: 16px;
            background-color: #2c3e50;
            color: var(--light-text);
            transition: all 0.3s ease;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 2px rgba(106, 147, 203, 0.2);
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-light);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #3d4852;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .btn-full {
            width: 100%;
        }
        
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .message i {
            font-size: 20px;
        }
        
        .success {
            background-color: rgba(38, 222, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(38, 222, 129, 0.3);
        }
        
        .error {
            background-color: rgba(252, 92, 101, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(252, 92, 101, 0.3);
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
    <!-- Header -->
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
                <a href="index.php" class="active">Book Service</a>
                <a href="service_history.php">Service History</a>
                <a href="admin.php">Admin Panel</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Expert Auto Care & Repair</h2>
            <p>Trust our certified mechanics to keep your vehicle running at peak performance with our comprehensive maintenance and repair services.</p>
            <div class="hero-buttons">
                <a href="#appointment-form" class="hero-btn primary-btn">Book Appointment</a>
                <a href="service_history.php" class="hero-btn secondary-btn">Check Service History</a>
            </div>
        </div>
    </section>

    <!-- Appointment Form Section -->
    <section class="appointment-section" id="appointment-form">
        <div class="container">
            <div class="section-title">
                <h2>Book Your Service</h2>
                <p>Schedule your car service appointment quickly and easily using our online booking system.</p>
            </div>
            
            <div class="appointment-card">
                <div class="card-header">
                    <h3><i class="fas fa-calendar-check"></i> Service Appointment Form</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="message success">
                            <i class="fas fa-check-circle"></i>
                            <div><?php echo $message; ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="message error">
                            <i class="fas fa-exclamation-circle"></i>
                            <div><?php echo $error; ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="">
                        <input type="hidden" name="action" value="book_appointment">
                        
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-user"></i> Personal Information
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="client_name">Full Name:</label>
                                    <input type="text" id="client_name" name="client_name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="client_phone">Phone Number:</label>
                                    <input type="text" id="client_phone" name="client_phone" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="client_address">Address:</label>
                                <textarea id="client_address" name="client_address" required></textarea>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-car"></i> Vehicle Information
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="car_make">Car Make:</label>
                                    <select id="car_make" name="car_make" required>
                                        <option value="">Select a make...</option>
                                        <?php foreach ($car_makes as $make): ?>
                                            <option value="<?php echo $make; ?>"><?php echo $make; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="car_model">Car Model:</label>
                                    <input type="text" id="car_model" name="car_model" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="car_license">License Plate Number:</label>
                                    <input type="text" id="car_license" name="car_license" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="car_engine">Engine Number:</label>
                                    <input type="text" id="car_engine" name="car_engine" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-tools"></i> Service Details
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="service_type">Service Type:</label>
                                    <select id="service_type" name="service_type" required>
                                        <option value="">Select service...</option>
                                        <?php foreach ($service_types as $service): ?>
                                            <option value="<?php echo $service; ?>"><?php echo $service; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="mechanic_id">Preferred Mechanic:</label>
                                    <select id="mechanic_id" name="mechanic_id" required>
                                        <option value="">Choose a mechanic...</option>
                                        <?php 
                                        // Reset the result pointer
                                        $mechanics_query = "SELECT * FROM mechanics ORDER BY name";
                                        $mechanics_result = $conn->query($mechanics_query);
                                        
                                        while ($mechanic = $mechanics_result->fetch_assoc()): 
                                        ?>
                                            <option value="<?php echo $mechanic['id']; ?>">
                                                <?php echo htmlspecialchars($mechanic['name'] . ' - ' . $mechanic['specialization']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="appointment_date">Appointment Date:</label>
                                    <input type="date" id="appointment_date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="appointment_time">Preferred Time:</label>
                                    <select id="appointment_time" name="appointment_time" required>
                                        <option value="">Select time...</option>
                                        <?php foreach ($time_slots as $time): ?>
                                            <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-full">Book Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-us-section">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Us</h2>
                <p>We are committed to providing the highest quality service for your vehicle</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 class="feature-title">Certified Mechanics</h3>
                    <p class="feature-description">Our team consists of fully certified and experienced mechanics who are experts in their field.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="feature-title">Advanced Equipment</h3>
                    <p class="feature-description">We use the latest diagnostic and repair equipment to ensure accurate and efficient service.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3 class="feature-title">Competitive Pricing</h3>
                    <p class="feature-description">Quality service doesn't have to be expensive. We offer fair and transparent pricing.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Quick Turnaround</h3>
                    <p class="feature-description">We value your time and work efficiently to get you back on the road as soon as possible.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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