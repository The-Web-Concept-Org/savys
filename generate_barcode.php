<?php
include_once 'includes/head.php';
require_once 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorSVG;

if (isset($_REQUEST['id'])) {
    $fetchproduct = fetchRecord($dbc, "purchase_item", "purchase_item_id", base64_decode($_REQUEST['id']));
    $fetchproductData = fetchRecord($dbc, "product", "product_id", $fetchproduct['product_id']);
    $fetchRackData = fetchRecord($dbc, "racks", "rack_id", $fetchproduct['rack_id']);

    $generator = new BarcodeGeneratorSVG();
    $barcodeSVG = $generator->getBarcode($fetchproduct['rack_number'], $generator::TYPE_CODE_128);

    echo "
<style>
    @page {
        size: 3in 2in; /* Label size */
        margin: 0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        width: 3in;
        height: 2in;
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: Arial, sans-serif;
    }
    .barcode-label-main {
        width: 3in;
        height: 2in;
        // border: 2px dotted red;
        padding: 0.1in;
    }
    .barcode-label {
        // border: 2px dotted green;
        height: 100%;
        margin-left: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start; /* Important to keep items top-aligned */
        text-align: center;
        padding: 0.1in;
    }
    

    .barcode-label svg {
        width: 90%;
        height: auto;
    }

    .label-text {
        font-size: 12px;
        font-weight: bold;
        margin: 2px 0;
    }

    .brand {
        font-size: 10px;
        font-style: italic;
        margin-top: auto; /* Push to bottom */
        text-align: center;
    }
    
    .label-row {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 0 2px;
        font-size: 12px;
        margin: 2px 0;
    }
    .label-row .label-text {
        flex: 1;
        text-align: left;
    }
    .label-row .label-text:last-child {
        text-align: right;
    }
    .product-name {
        text-align: left;
        font-size: 14px;
        font-weight: bold;
        width: 100%;
        color: #000;
        padding: 2px;
    }
    
    

    @media print {
        body {
            margin: 0;
        }
    
        .barcode-label-main {
            // border: 2px dotted red !important;
        }
    
        .barcode-label {
            // border: 2px dotted green !important;
            page-break-after: avoid;
        }
    
        .label-text, .label-row, .brand {
            color: #000 !important;
        }
    }
    
</style>
<div class='barcode-label-main'>
    <div class='barcode-label'>
        <div class='barcode'>$barcodeSVG</div>
        <div class='label-text'>" . htmlspecialchars(ucwords($fetchproduct['rack_number'])) . "</div>
        <div class='label-row'>
            <div class='label-text'>Zone: <strong>" . htmlspecialchars(ucwords($fetchRackData['zone'])) . "</strong></div>
            <div class='label-text'>Rack No: <strong>" . htmlspecialchars(ucwords($fetchRackData['name'])) . "</strong></div>
        </div>
        <div class='label-text product-name'>" . htmlspecialchars(ucwords($fetchproductData['product_name'])) . "</div>
        <div class='brand'>Savys</div>
    </div>
</div>
";
}
?>

<?php include_once 'includes/foot.php'; ?>
