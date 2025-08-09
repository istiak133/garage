<?php
include 'config.php';

$message = "";
$error = "";

// Handle appointment updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_appointment'])) {
        $appointment_id = (int)$_POST['appointment_id'];
        $new_date = $_POST['new_date'];
        $new_mechanic_id = (int)$_POST['new_mechanic_id'];
        
        // Check if new mechanic is available on new date using prepared statement
        $check_availability = "SELECT COUNT(*) as count FROM appointments WHERE mechanic_id = ? AND appointment_date = ? AND status = 'pending' AND id != ?";
        $stmt = $conn->prepare($check_availability);
        $stmt->bind_param("isi", $new_mechanic_id, $new_date, $appointment_id);
        $stmt->execute();
        $availability_result = $stmt->get_result();
        $current_count = $availability_result->fetch_assoc()['count'];
        
        if ($current_count >= 4) {
            $error = "Selected mechanic is already fully booked on the new date (4 appointments max per day).";
        } else {
            // Update the appointment using prepared statement
            $update_query = "UPDATE appointments SET appointment_date = ?, mechanic_id = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sii", $new_date, $new_mechanic_id, $appointment_id);
            
            if ($stmt->execute()) {
                $message = "Appointment updated successfully!";
            } else {
                $error = "Error updating appointment: " . $conn->error;
            }
        }
    }
    
    // Handle status updates
    if (isset($_POST['update_status'])) {
        $appointment_id = (int)$_POST['appointment_id'];
        $new_status = $_POST['new_status'];
        
        // Update status using prepared statement
        $status_query = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($status_query);
        $stmt->bind_param("si", $new_status, $appointment_id);
        
        if ($stmt->execute()) {
            $message = "Appointment status updated successfully!";
        } else {
            $error = "Error updating status: " . $conn->error;
        }
    }
}

// Get all appointments with mechanic details
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

$query_params = [];
$appointments_query = "
    SELECT a.*, m.name as mechanic_name, m.specialization 
    FROM appointments a 
    JOIN mechanics m ON a.mechanic_id = m.id 
    WHERE 1=1
";

if (!empty($search_term)) {
    $appointments_query .= " AND (a.client_name LIKE ? OR a.client_phone LIKE ? OR a.car_license LIKE ?)";
    $search_param = "%$search_term%";
    $query_params[] = $search_param;
    $query_params[] = $search_param;
    $query_params[] = $search_param;
}

if (!empty($status_filter)) {
    $appointments_query .= " AND a.status = ?";
    $query_params[] = $status_filter;
}

$appointments_query .= " ORDER BY a.appointment_date DESC, a.created_at DESC";

$stmt = $conn->prepare($appointments_query);

if (!empty($query_params)) {
    $types = str_repeat("s", count($query_params));
    $stmt->bind_param($types, ...$query_params);
}

$stmt->execute();
$appointments_result = $stmt->get_result();

// Get all mechanics for dropdown
$mechanics_query = "SELECT * FROM mechanics ORDER BY name";
$mechanics_result = $conn->query($mechanics_query);

// Get statistics
$stats_query = "
    SELECT 
        COUNT(*) as total_appointments,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_appointments,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_appointments,
        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_appointments,
        COUNT(CASE WHEN appointment_date = CURDATE() THEN 1 END) as today_appointments
    FROM appointments
";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();
?>

<?php include 'header.php'; ?>

<div class="page-content">
    <div class="container">
        <!-- Admin Page Intro -->
        <div class="page-intro">
            <h2><i class="fas fa-user-shield"></i> Admin Dashboard</h2>
            <p>Manage appointments, track service history, and update service status.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Stats Dashboard -->
        <div class="stats-dashboard">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['total_appointments']; ?></div>
                        <div class="stat-label">Total Appointments</div>
                    </div>
                </div>
                
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['pending_appointments']; ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
                
                <div class="stat-card completed">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['completed_appointments']; ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
                
                <div class="stat-card cancelled">
                    <div class="stat-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['cancelled_appointments']; ?></div>
                        <div class="stat-label">Cancelled</div>
                    </div>
                </div>
                
                <div class="stat-card today">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['today_appointments']; ?></div>
                        <div class="stat-label">Today's Appointments</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="card">
                <div class="card-header">
                    <h3>Filter Appointments</h3>
                </div>
                <div class="card-body">
                    <form method="get" action="" class="filter-form">
                        <div class="form-group">
                            <label for="search">Search:</label>
                            <div class="input-with-icon">
                                <i class="fas fa-search"></i>
                                <input type="text" id="search" name="search" placeholder="Client name, phone or license plate" 
                                       value="<?php echo htmlspecialchars($search_term); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="status_filter">Status:</label>
                            <div class="select-wrapper">
                                <select id="status_filter" name="status_filter">
                                    <option value="">All Statuses</option>
                                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                            <a href="admin.php" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="section-title">
            <h3>Appointment List</h3>
            <p>Showing <?php echo $appointments_result->num_rows; ?> appointments</p>
        </div>
        
        <div class="appointments-container">
            <?php if ($appointments_result->num_rows === 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    No appointments found matching your criteria. Try adjusting your filters.
                </div>
            <?php else: ?>
                <div class="appointments-table-wrapper">
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client Info</th>
                                <th>Vehicle Details</th>
                                <th>Service</th>
                                <th>Date & Time</th>
                                <th>Mechanic</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $appointment['id']; ?></td>
                                    <td>
                                        <div class="client-info">
                                            <div class="client-name"><?php echo htmlspecialchars($appointment['client_name']); ?></div>
                                            <div class="client-phone"><?php echo htmlspecialchars($appointment['client_phone']); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="vehicle-info">
                                            <div class="vehicle-model"><?php echo htmlspecialchars($appointment['car_make'] . ' ' . $appointment['car_model']); ?></div>
                                            <div class="vehicle-license"><?php echo htmlspecialchars($appointment['car_license']); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="service-type"><?php echo htmlspecialchars($appointment['service_type']); ?></div>
                                    </td>
                                    <td>
                                        <div class="appointment-date">
                                            <i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                        </div>
                                        <div class="appointment-time">
                                            <i class="far fa-clock"></i> <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mechanic-info">
                                            <div class="mechanic-name"><?php echo htmlspecialchars($appointment['mechanic_name']); ?></div>
                                            <div class="mechanic-specialty"><?php echo htmlspecialchars($appointment['specialization']); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($appointment['status']); ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-icon btn-edit" title="Edit Appointment" 
                                                    onclick="openEditModal(<?php echo $appointment['id']; ?>, '<?php echo $appointment['appointment_date']; ?>', <?php echo $appointment['mechanic_id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <button class="btn-icon btn-status" title="Change Status"
                                                    onclick="openStatusModal(<?php echo $appointment['id']; ?>, '<?php echo $appointment['status']; ?>')">
                                                <i class="fas fa-tasks"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reschedule Appointment</h3>
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form method="post" action="" id="editForm">
                <input type="hidden" name="appointment_id" id="edit_appointment_id">
                <div class="form-group">
                    <label for="new_date">New Appointment Date:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" id="new_date" name="new_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="new_mechanic_id">Assign to Mechanic:</label>
                    <div class="select-wrapper">
                        <select id="new_mechanic_id" name="new_mechanic_id" required>
                            <?php 
                            $mechanics_result->data_seek(0);
                            while ($mechanic = $mechanics_result->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $mechanic['id']; ?>">
                                    <?php echo $mechanic['name']; ?> (<?php echo $mechanic['specialization']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="update_appointment" class="btn btn-primary">
                        <i class="fas fa-calendar-check"></i> Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Appointment Status</h3>
            <span class="close-modal" onclick="closeStatusModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form method="post" action="" id="statusForm">
                <input type="hidden" name="appointment_id" id="status_appointment_id">
                <div class="form-group">
                    <label for="new_status">New Status:</label>
                    <div class="select-wrapper">
                        <select id="new_status" name="new_status" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="update_status" class="btn btn-primary">
                        <i class="fas fa-check"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom CSS for Admin Page -->
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
    
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background-color: rgba(38, 222, 129, 0.2);
        border: 1px solid var(--success-color);
        color: var(--success-color);
    }
    
    .alert-danger {
        background-color: rgba(252, 92, 101, 0.2);
        border: 1px solid var(--error-color);
        color: var(--error-color);
    }
    
    .alert-info {
        background-color: rgba(58, 110, 165, 0.2);
        border: 1px solid var(--primary-color);
        color: var(--light-text);
    }
    
    .stats-dashboard {
        margin-bottom: 30px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }
    
    .stat-card {
        background-color: var(--card-bg);
        border-radius: 8px;
        padding: 20px;
        display: flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        background-color: rgba(58, 110, 165, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .stat-icon i {
        font-size: 22px;
        color: var(--primary-light);
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--light-text);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 14px;
        color: var(--gray-text);
    }
    
    .stat-card.pending .stat-icon {
        background-color: rgba(255, 152, 0, 0.2);
    }
    
    .stat-card.pending .stat-icon i {
        color: #FFA000;
    }
    
    .stat-card.completed .stat-icon {
        background-color: rgba(46, 204, 113, 0.2);
    }
    
    .stat-card.completed .stat-icon i {
        color: #2ECC71;
    }
    
    .stat-card.cancelled .stat-icon {
        background-color: rgba(231, 76, 60, 0.2);
    }
    
    .stat-card.cancelled .stat-icon i {
        color: #E74C3C;
    }
    
    .stat-card.today .stat-icon {
        background-color: rgba(52, 152, 219, 0.2);
    }
    
    .stat-card.today .stat-icon i {
        color: #3498DB;
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
    
    .filter-form {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
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
    
    .select-wrapper {
        position: relative;
    }
    
    .select-wrapper::after {
        content: "\f078";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-text);
        pointer-events: none;
    }
    
    select {
        width: 100%;
        padding: 12px 15px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background-color: rgba(0, 0, 0, 0.2);
        color: var(--light-text);
        font-size: 16px;
        appearance: none;
        cursor: pointer;
    }
    
    select:focus {
        outline: none;
        border-color: var(--primary-light);
        box-shadow: 0 0 0 2px rgba(58, 110, 165, 0.3);
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
        font-size: 16px;
    }
    
    .btn i {
        margin-right: 8px;
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
    
    .btn-outline {
        background-color: transparent;
        color: var(--light-text);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .btn-outline:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
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
    
    .appointments-table-wrapper {
        overflow-x: auto;
        background-color: var(--card-bg);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .appointments-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .appointments-table th {
        background-color: rgba(0, 0, 0, 0.2);
        color: var(--light-text);
        text-align: left;
        padding: 15px;
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .appointments-table td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--light-text);
    }
    
    .appointments-table tr:hover {
        background-color: rgba(255, 255, 255, 0.03);
    }
    
    .appointments-table tr:last-child td {
        border-bottom: none;
    }
    
    .client-name, .mechanic-name, .vehicle-model {
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .client-phone, .mechanic-specialty, .vehicle-license {
        font-size: 14px;
        color: var(--gray-text);
    }
    
    .appointment-date {
        margin-bottom: 4px;
    }
    
    .appointment-date i, .appointment-time i {
        color: var(--primary-light);
        margin-right: 5px;
    }
    
    .status-badge {
        display: inline-block;
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
    
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }
    
    .btn-edit {
        background-color: rgba(52, 152, 219, 0.2);
        color: #3498DB;
    }
    
    .btn-edit:hover {
        background-color: rgba(52, 152, 219, 0.4);
    }
    
    .btn-status {
        background-color: rgba(243, 156, 18, 0.2);
        color: #F39C12;
    }
    
    .btn-status:hover {
        background-color: rgba(243, 156, 18, 0.4);
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        overflow: auto;
    }
    
    .modal-content {
        background-color: var(--card-bg);
        margin: 10% auto;
        border-radius: 8px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: modalFadeIn 0.3s;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h3 {
        margin: 0;
        color: var(--light-text);
    }
    
    .close-modal {
        color: var(--gray-text);
        font-size: 24px;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    .close-modal:hover {
        color: var(--light-text);
    }
    
    .modal-body {
        padding: 20px;
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
        .filter-form {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .filter-form {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .appointments-table {
            min-width: 800px;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- JavaScript for Modal Functionality -->
<script>
    // Edit Modal
    function openEditModal(appointmentId, currentDate, mechanicId) {
        document.getElementById('edit_appointment_id').value = appointmentId;
        document.getElementById('new_date').value = currentDate;
        document.getElementById('new_mechanic_id').value = mechanicId;
        document.getElementById('editModal').style.display = 'block';
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    
    // Status Modal
    function openStatusModal(appointmentId, currentStatus) {
        document.getElementById('status_appointment_id').value = appointmentId;
        document.getElementById('new_status').value = currentStatus;
        document.getElementById('statusModal').style.display = 'block';
    }
    
    function closeStatusModal() {
        document.getElementById('statusModal').style.display = 'none';
    }
    
    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) {
            closeEditModal();
        }
        if (event.target == document.getElementById('statusModal')) {
            closeStatusModal();
        }
    }
</script>

<?php include 'footer.php'; ?>
