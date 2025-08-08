<?php
ob_start();

error_log('fetch_purchase_items.php: Script started');

// Include the core file
include_once '../php_action/core.php';

if (!isset($dbc) || !$dbc) {
    error_log('fetch_purchase_items.php: Database connection not set or invalid');
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}

if (!mysqli_ping($dbc)) {
    error_log('fetch_purchase_items.php: Database connection failed ping test');
    echo json_encode(['success' => false, 'message' => 'Database connection ping failed']);
    exit;
}

error_log('fetch_purchase_items.php: Database connection OK');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log('fetch_purchase_items.php: Invalid request method - ' . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Log POST data
error_log('fetch_purchase_items.php: POST data received - ' . print_r($_POST, true));

// Check if purchase_id is provided
if (!isset($_POST['purchase_id']) || empty($_POST['purchase_id'])) {
    error_log('fetch_purchase_items.php: Purchase ID not provided');
    echo json_encode(['success' => false, 'message' => 'Purchase ID is required']);
    exit;
}

$purchase_id = intval($_POST['purchase_id']);
error_log('fetch_purchase_items.php: Processing purchase_id = ' . $purchase_id);

try {
    // First, check if the purchase exists
    $check_query = "SELECT COUNT(*) as count FROM purchase WHERE purchase_id = ?";
    $check_stmt = mysqli_prepare($dbc, $check_query);
    if (!$check_stmt) {
        error_log('fetch_purchase_items.php: Check query prepare failed - ' . mysqli_error($dbc));
        echo json_encode(['success' => false, 'message' => 'Database prepare error on check query']);
        exit;
    }
    mysqli_stmt_bind_param($check_stmt, "i", $purchase_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $check_row = mysqli_fetch_assoc($check_result);
    
    if ($check_row['count'] == 0) {
        error_log('fetch_purchase_items.php: Purchase ID ' . $purchase_id . ' not found');
        echo json_encode(['success' => false, 'message' => 'Purchase not found']);
        exit;
    }
    
    error_log('fetch_purchase_items.php: Purchase found, fetching items');

    // Fetch purchase items for the given purchase_id
    $query = "SELECT 
                pi.purchase_item_id,
                pi.rack_number,
                pi.quantity,
                pi.rate,
                p.product_name,
                p.product_image
              FROM purchase_item pi
              LEFT JOIN product p ON pi.product_id = p.product_id
              WHERE pi.purchase_id = ?";
    
    $stmt = mysqli_prepare($dbc, $query);
    if (!$stmt) {
        error_log('fetch_purchase_items.php: MySQL prepare failed - ' . mysqli_error($dbc));
        echo json_encode(['success' => false, 'message' => 'Database prepare error']);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "i", $purchase_id);
    $execute_result = mysqli_stmt_execute($stmt);
    if (!$execute_result) {
        error_log('fetch_purchase_items.php: MySQL execute failed - ' . mysqli_stmt_error($stmt));
        echo json_encode(['success' => false, 'message' => 'Database execute error']);
        exit;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log('fetch_purchase_items.php: MySQL get_result failed - ' . mysqli_error($dbc));
        echo json_encode(['success' => false, 'message' => 'Database result error']);
        exit;
    }
    
    $items = [];
    $row_count = mysqli_num_rows($result);
    error_log('fetch_purchase_items.php: Found ' . $row_count . ' items');
    
    if ($row_count > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'purchase_item_id' => $row['purchase_item_id'],
                'rack_number' => $row['rack_number'],
                'product_name' => $row['product_name'] ? ucfirst($row['product_name']) : 'Unknown Product',
                'quantity' => $row['quantity'],
                'rate' => $row['rate'],
                'product_image' => !empty($row['product_image']) ? './img/uploads/' . $row['product_image'] : './img/uploads/SAVYS_Logo_Final.png'
            ];
        }
    }
    
    error_log('fetch_purchase_items.php: Successfully processed ' . count($items) . ' items');
    
    // Clear any output buffer
    ob_clean();
    
    echo json_encode([
        'success' => true,
        'items' => $items
    ]);
    
} catch (Exception $e) {
    error_log('fetch_purchase_items.php: Exception - ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

// End output buffering and send response
ob_end_flush();
?> 