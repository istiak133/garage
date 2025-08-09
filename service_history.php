<?php
include 'config.php';

$appointments = [];
$search_performed = false;

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_phone = mysqli_real_escape_string($conn, $_POST['client_phone']);
    $car_engine = mysqli_real_escape_string($conn, $_POST['car_engine']);
    
    // Search for appointments using phone and engine number
    $search_query = "
        SELECT a.*, m.name as mechanic_name, m.specialization 
        FROM appointments a 
        JOIN mechanics m ON a.mechanic_id = m.id 
        WHERE a.client_phone = '$client_phone' AND a.car_engine = '$car_engine'
        ORDER BY a.appointment_date DESC
    ";
    
    $result = $conn->query($search_query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }
    
    $search_performed = true;
}
?>

<?php include 'header.php'; ?>

<div class="page-content">
    <div class="container">
        <!-- Page Intro -->
        <div class="page-intro">
            <h2><i class="fas fa-history"></i> Service History</h2>
            <p>View your past and upcoming service appointments by entering your phone number and car engine number below.</p>
        </div>

        <!-- Search Form Card -->
        <div class="card">
            <div class="card-header">
                <h3>Search Your Service History</h3>
            </div>
            <div class="card-body">
                <form method="post" action="" class="form-grid">
                    <div class="form-group">
                        <label for="client_phone">Phone Number:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-phone"></i>
                            <input type="text" id="client_phone" name="client_phone" required 
                                   placeholder="Enter your phone number"
                                   value="<?php echo isset($_POST['client_phone']) ? htmlspecialchars($_POST['client_phone']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="car_engine">Car Engine Number:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-cog"></i>
                            <input type="text" id="car_engine" name="car_engine" required
                                   placeholder="Enter your car engine number"
                                   value="<?php echo isset($_POST['car_engine']) ? htmlspecialchars($_POST['car_engine']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search History
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <?php if ($search_performed): ?>
            <?php if (empty($appointments)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    No service history found for the provided details. Please check the information and try again.
                </div>
            <?php else: ?>
                <div class="section-title">
                    <h3>Service History Results</h3>
                    <p>Found <?php echo count($appointments); ?> service record(s)</p>
                </div>
                
                <div class="card history-card">
                    <div class="history-list">
                        <?php foreach ($appointments as $appointment): ?>
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="history-date">
                                        <i class="far fa-calendar-alt"></i>
                                        <?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?> at 
                                        <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                    </div>
                                    <div class="history-status status-<?php echo strtolower($appointment['status']); ?>">
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </div>
                                </div>
                                
                                <div class="history-content">
                                    <div class="history-details">
                                        <div class="detail-group">
                                            <span class="detail-label">Service Type:</span>
                                            <span class="detail-value"><?php echo $appointment['service_type']; ?></span>
                                        </div>
                                        <div class="detail-group">
                                            <span class="detail-label">Vehicle:</span>
                                            <span class="detail-value"><?php echo $appointment['car_make'] . ' ' . $appointment['car_model']; ?></span>
                                        </div>
                                        <div class="detail-group">
                                            <span class="detail-label">License Plate:</span>
                                            <span class="detail-value"><?php echo $appointment['car_license']; ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="history-mechanic">
                                        <div class="mechanic-info">
                                            <i class="fas fa-user-cog"></i>
                                            <div>
                                                <span class="mechanic-name"><?php echo $appointment['mechanic_name']; ?></span>
                                                <span class="mechanic-specialty"><?php echo $appointment['specialization']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Custom CSS for Service History Page -->
<style>
    .page-intro {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .page-intro h2 {
        font-size: 32px;
        margin-bottom: 10px;
        color: var(--light-text);
    }
    
    .page-intro p {
        font-size: 16px;
        color: var(--gray-text);
        max-width: 700px;
        margin: 0 auto;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .form-group:last-child {
        grid-column: span 2;
        text-align: center;
        margin-top: 10px;
    }
    
    .card {
        background-color: var(--card-bg);
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .card-header {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 20px;
        color: var(--light-text);
    }
    
    .card-body {
        padding: 20px;
    }
    
    .btn {
        display: inline-block;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
        border: 1px solid var(--primary-dark);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .section-title {
        margin: 40px 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-title h3 {
        font-size: 24px;
        color: var(--light-text);
        margin: 0;
    }
    
    .section-title p {
        color: var(--gray-text);
        margin: 0;
    }
    
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-warning {
        background-color: rgba(243, 156, 18, 0.2);
        border: 1px solid var(--warning-color);
        color: var(--warning-color);
    }
    
    .history-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .history-item {
        padding: 20px;
        border-radius: 6px;
        background-color: rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .history-date {
        font-size: 16px;
        color: var(--light-text);
    }
    
    .history-date i {
        margin-right: 5px;
        color: var(--primary-light);
    }
    
    .history-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: rgba(255, 152, 0, 0.2);
        color: #FFA000;
    }
    
    .status-completed {
        background-color: rgba(46, 204, 113, 0.2);
        color: #2ECC71;
    }
    
    .status-cancelled {
        background-color: rgba(231, 76, 60, 0.2);
        color: #E74C3C;
    }
    
    .history-content {
        display: flex;
        justify-content: space-between;
    }
    
    .history-details {
        flex: 1;
    }
    
    .detail-group {
        margin-bottom: 8px;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--gray-text);
        margin-right: 5px;
    }
    
    .detail-value {
        color: var(--light-text);
    }
    
    .history-mechanic {
        width: 200px;
        text-align: right;
    }
    
    .mechanic-info {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .mechanic-info i {
        font-size: 24px;
        color: var(--primary-light);
    }
    
    .mechanic-name {
        display: block;
        font-weight: 600;
        color: var(--light-text);
    }
    
    .mechanic-specialty {
        display: block;
        font-size: 14px;
        color: var(--gray-text);
    }
    
    .input-with-icon {
        position: relative;
    }
    
    .input-with-icon i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-text);
    }
    
    .input-with-icon input {
        padding-left: 40px;
        width: 100%;
        padding: 12px 12px 12px 40px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background-color: rgba(0, 0, 0, 0.2);
        color: var(--light-text);
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .input-with-icon input:focus {
        outline: none;
        border-color: var(--primary-light);
        box-shadow: 0 0 0 2px rgba(58, 110, 165, 0.3);
    }
    
    .input-with-icon input::placeholder {
        color: rgba(164, 176, 190, 0.6);
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-group:last-child {
            grid-column: 1;
        }
        
        .history-content {
            flex-direction: column;
        }
        
        .history-mechanic {
            width: 100%;
            text-align: left;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .mechanic-info {
            justify-content: flex-start;
        }
    }
</style>

<?php include 'footer.php'; ?>
