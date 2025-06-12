<?php
include_once 'includes/head.php';
require_once 'vendor/autoload.php'; // Include the Composer autoloader

use Picqer\Barcode\BarcodeGeneratorSVG;

// Check for the product ID
if (isset($_REQUEST['id'])) {
    // Fetch product data

    // Generate SVG barcode


    $fetchproduct = fetchRecord($dbc, "purchase_item", "purchase_item_id", base64_decode($_REQUEST['id']));
    $fetchproductData = fetchRecord($dbc, "product", "product_id", $fetchproduct['product_id']);
    $fetchRackData = fetchRecord($dbc, "racks", "rack_id", $fetchproduct['rack_id']);

    $generator = new BarcodeGeneratorSVG();
    $barcodeSVG = $generator->getBarcode($fetchproduct['rack_number'], $generator::TYPE_CODE_11);

    // Output HTML
    echo "
<style>
    .barcode-label {
        width: 220px;
        border: 1px solid #ddd;
        padding: 10px;
        margin: 10px auto;
        font-family: Arial, sans-serif;
        text-align: center;
        box-shadow: 0 0 6px rgba(0,0,0,0.1);
        border-radius: 8px;
        background-color: #fff;
    }
    .barcode-label .barcode {
        margin-bottom: 8px;
    }
    .barcode-label .label-text {
        margin: 2px 0;
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }
    .barcode-label .brand {
        font-size: 13px;
        font-style: italic;
        color: #555;
        margin-top: 6px;
    }
</style>

<div class='barcode-label'>
    <div class='barcode'>$barcodeSVG</div>
    <div class='label-text'>" . htmlspecialchars(ucwords($fetchproduct['rack_number'])) . "</div>
    <div class='label-text text-left'>" . htmlspecialchars(ucwords($fetchRackData['name'])) . "</div>
    <div class='label-text text-left'>" . htmlspecialchars(ucwords($fetchRackData['zone'])) . "</div>
    <div class='label-text text-left'>" . htmlspecialchars(ucwords($fetchproductData['product_name'])) . "</div>
    <div class='brand'>Savys</div>
</div>
";
}
?>

<!-- <style>
    body {
        margin: 0;
        padding: 0;
    }

    .printtest {
        width: 280px;
        text-align: center;
        padding: 5px;
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        .printtest {
            page-break-after: always;
            margin: 0;
            padding: 0;
        }
    }
</style> -->

<?php include_once 'includes/foot.php'; ?>