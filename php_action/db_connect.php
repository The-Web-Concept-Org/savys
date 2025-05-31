<?php 	
date_default_timezone_set("Asia/Karachi");
  // $localhost = "localhost";
//  $username = "samziymw_techo_polly";
//  $password = "samziymw_techo_polly";
//  $dbname = "samziymw_techo_polly";
$localhost = "localhost";
 $username = "root";
 $password = "";
 $dbname = "butttraders";

$connect = new mysqli($localhost, $username, $password, $dbname);
$dbc =  mysqli_connect($localhost, $username, $password, $dbname);

@session_start();
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  //echo "Done";
}

?>