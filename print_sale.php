

<center>
<?php


require_once 'php_action/db_connect.php';

 $orderId = $_REQUEST['id'];

$get_company =mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM company ORDER BY id DESC LIMIT 1 "));

$sql = "SELECT * FROM orders WHERE order_id = $orderId";

$orderResult = $connect->query($sql);
$orderData = $orderResult->fetch_array();

$orderDate = $orderData[0];
$clientName = $orderData[1];
$clientContact = $orderData[2]; 
$subTotal = $orderData[3];
$vat = $orderData[4];
$totalAmount = $orderData[5]; 
$discount = $orderData['discount'];
$grandTotal = $orderData[7];
$paid = $orderData[8];
$due = $orderData[9];
$order_type = $orderData[10];
$address = $orderData[11];
$table = $orderData[12];


$orderItemSql = "SELECT order_item.product_id, order_item.rate, order_item.quantity, order_item.total,
product.product_name FROM order_item
    INNER JOIN product ON order_item.product_id = product.product_id 
 WHERE order_item.order_id = $orderId ";
$orderItemResult = $connect->query($orderItemSql);
if ( mysqli_num_rows($orderItemResult) > 0) {

 ?>
 <body>
 <table border="1" cellspacing="0" cellpadding="2" width="100%" style="font-size:20px;">
    <thead>
        <tr >
            <th colspan="5">
                <div align="center">
                <img src="img/logo/<?=$get_company['logo']?>" style="width: 100px;" ><br/>
                <h4><?=$get_company['name']?> </h>
                
                <p style="font-size: 18; margin-top: 0px;"><strong>Cell No:</strong> 
                    <?=$get_company['company_phone']?> <br/> <?=$get_company['personal_phone']?> </p>
                

                </div>


                    
            </th>
            
        </tr>   
        <tr >
            <th colspan="5">

            <div align="left">
                Bill No.: <strong><?php echo $orderData['order_id'] ; ?> </strong> <br />
                Order Date : <strong><?php echo $orderData['order_date'] ; ?> </strong><br />
                Client Name : <strong> <?php echo $orderData['client_name'] ; ?></strong><br />
                Contact :<strong> <?php echo $orderData['client_contact'] ; ?></strong><br/>
               
               
                
            </div>

            </th>
            </tr>

        <tr >
            <th colspan="5">

            <center>
                
            </center>       
            </th>
                
        </tr>       
    </thead>
</table>
<table border="1" width="90%;" cellpadding="1" style="border:1px solid black;font-size:20px;border-top-style:1px solid black;border-bottom-style:1px solid black ;">

    <tbody>
        <tr>
            <th>S.no</th>
            <th>Product</th>
            <th>Rate</th> 
            <th>QTY</th>
            
            <th>Total</th>
        </tr>
        <?php
        $x = 1; 
        $subamount = 0;
        $totaldisc = 0;
        $grand_total_show = 0;
        while($row = $orderItemResult->fetch_array()) {
                $product_id = $row['product_id'];
                $fetchProduct = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM product WHERE product_id='$product_id'"));
                $fetchCategory = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM categories WHERE categories_id='$fetchProduct[category_id]'"));
            
        ?>              
             <tr>
                <th><?php echo $x ?></th>
                
<?php
if(empty($fetchProduct['product_name_urdu'])){
?>
<td style="font-size:20px;">
                    <?= $fetchProduct['product_name']?> </td>
<?php
}else{
    ?>
    <td style="font-size:20px;">
                     <?= $fetchProduct['product_name_urdu']?> </td>
<?php
   
}

?>



                <th><?php echo $row['rate']; ?></th>
                <?php
                $fetchprorate =  $row['rate']*$row[2];
                ?>
                <th><?php echo $row[2]; ?></th>
                
                <th><?php echo $fetchprorate ; ?></th>


        <?php

        //$subamount +=  $row[3]-$totel;
        $subamount += $fetchprorate;
        
        $x++;
        } // /while
?>
        </tr>
</tbody>


</table>
        
        <table style="float: right; font-size:25px;margin-right: 40px" border="1px ">
        <tr > 
            <td>Sub Amount</td>
            <td><?php echo $subamount; ?></td>          
        </tr>
            <?php
                if ($discount>0) {
                                ?>
         <tr>
            <td>Discount (Rs.)</td>
            <td><?php echo $discount; ?></td>           
        </tr> 

         <tr>
            <td>Grand Total</td>
            <td><?php echo $subamount-$discount; ?></td>           
        </tr> 
        <?php
}
        ?>

       

        <tr>
            <td>Cash Received   </td>
            <td><?php echo $orderData['paid']; ?></td>           
        </tr>

        <tr>
            <td>Returnable  </td>
            <td><?php
                $grandthis = $subamount-$discount;
             echo abs($grandthis-$orderData['paid']); ?></td>            
        </tr>
    </div>  
</table>
        
        
<div style="margin-top:120px;">  

    <p style="font-size: 20px">
        ہمارے ہاں ڈی سی ریٹ کی دالیں دستیاب ہے
    </p>
    <p style="font-size: 20px">
        کیس بی شیکات کی صورت میں رابطہ کریں
    </p>

<p style="margin-top:0px;font-size:14px"><strong>Software Developed By: <br/> SAM'Z Creation<br/>(0345-7573667)</strong></p>
</div> <br/>
<?php
}
?>

</body>
</center>