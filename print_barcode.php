<?php
include_once 'includes/head.php';
	if(ISSET($_REQUEST['id'])){
		$fetchproduct=fetchRecord($dbc,"product", "product_id",base64_decode($_REQUEST['id']));

for($i=1;$i<=1;$i++){
	
echo "<div class='bg-white printtest ' style='width:auto;height:auto;text-align:center;position:fixed;top:0px;'>

 <img alt='test'
       src='https://barcode.tec-it.com/barcode.ashx?data=".$fetchproduct['product_code']."&code=Code128' style='margin-top:-40px'/>
 <p class='text-center p-0 m-0 border-top' 
style='' clear: both;
    display: inline-block;
    overflow: hidden;
    white-space: nowrap;'
 > ".$fetchproduct['product_name']."
<br/> ".date('d-m-Y')."
 </p></div>";
    ?>
   
    <?php
}
	}
?>
<style>
    @media print {
  .printtest {page-break-after: always;}
}
</style>


<?php 	include_once 'includes/foot.php'; ?>

