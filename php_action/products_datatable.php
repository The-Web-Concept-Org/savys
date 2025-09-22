<?php
require_once 'core.php';
require_once 'db_connect.php';

header('Content-Type: application/json');

// DataTables parameters
$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

// Optional: stock_manage sent from client to decide if quantity column is shown
$stockManage = isset($_POST['stock_manage']) ? (int)$_POST['stock_manage'] : 1;

// Column mapping for ordering: keep in sync with product.php initialization
// Only enable ordering on meaningful text/ids; image/actions are not ordered
$columns = [
    0 => 'p.product_id',      // row number / id
    1 => null,                // image (no order)
    2 => 'p.product_code',
    3 => 'p.product_name',
    4 => 'b.brand_name',
    5 => 'c.categories_name',
    6 => null,                // qty or action depending on stock_manage
    7 => null,                // action when stock_manage enabled
];

// Determine order
$orderColumnIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$orderDir = isset($_POST['order'][0]['dir']) && in_array(strtoupper($_POST['order'][0]['dir']), ['ASC','DESC'])
    ? strtoupper($_POST['order'][0]['dir'])
    : 'DESC';
$orderBy = $columns[$orderColumnIndex] ?? 'p.product_id';

// Base query with joins and aggregated inventory
$baseFrom = " FROM product p
LEFT JOIN brands b ON b.brand_id = p.brand_id
LEFT JOIN categories c ON c.categories_id = p.category_id
LEFT JOIN (
  SELECT product_id, SUM(quantity_instock) AS quantity_instock
  FROM inventory
  GROUP BY product_id
) inv ON inv.product_id = p.product_id
WHERE p.status = 1";

// Total records
$countSql = "SELECT COUNT(*) AS cnt" . $baseFrom;
$countRes = mysqli_query($dbc, $countSql);
$recordsTotal = 0;
if ($countRes) {
    $row = mysqli_fetch_assoc($countRes);
    $recordsTotal = (int)$row['cnt'];
}

// Filtering
$whereFilter = '';
if ($searchValue !== '') {
    $safe = mysqli_real_escape_string($dbc, $searchValue);
    $like = "'%" . $safe . "%'";
    $whereFilter = " AND (p.product_code LIKE $like OR p.product_name LIKE $like OR IFNULL(p.color,'') LIKE $like OR IFNULL(p.size,'') LIKE $like OR IFNULL(b.brand_name,'') LIKE $like OR IFNULL(c.categories_name,'') LIKE $like)";
}

// Filtered count
$countFilteredSql = "SELECT COUNT(*) AS cnt" . $baseFrom . $whereFilter;
$countFilteredRes = mysqli_query($dbc, $countFilteredSql);
$recordsFiltered = $recordsTotal;
if ($countFilteredRes) {
    $row = mysqli_fetch_assoc($countFilteredRes);
    $recordsFiltered = (int)$row['cnt'];
}

// Data query
$selectFields = "SELECT p.product_id, p.product_image, p.product_code, p.product_name, p.color, p.size, p.alert_at, IFNULL(b.brand_name,'') AS brand_name, IFNULL(c.categories_name,'') AS categories_name, IFNULL(inv.quantity_instock, 0) AS quantity_instock";
$dataSql = $selectFields . $baseFrom . $whereFilter . " ORDER BY $orderBy $orderDir LIMIT $start, $length";
$dataRes = mysqli_query($dbc, $dataSql);

$data = [];
if ($dataRes) {
    while ($r = mysqli_fetch_assoc($dataRes)) {
        $data[] = [
            'row_id' => (int)$r['product_id'],
            'product_id' => (int)$r['product_id'],
            'product_image' => (string)($r['product_image'] ?: ''),
            'product_code' => (string)$r['product_code'],
            'product_name' => (string)$r['product_name'],
            'color' => (string)($r['color'] ?: ''),
            'size' => (string)($r['size'] ?: ''),
            'brand_name' => (string)$r['brand_name'],
            'categories_name' => (string)$r['categories_name'],
            'quantity_instock' => (float)$r['quantity_instock'],
            'alert_at' => (float)$r['alert_at'],
        ];
    }
}

echo json_encode([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data,
]);

?>

