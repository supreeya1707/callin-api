<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();
$vn = $_GET['vn'];

$sql = "SELECT
        finance_number, 
        rcpno, 
        bill_amount, 
        bill_date_time, 
        `user`, 
        hn,
        vn, 
        `status`, 
        total_amount, 
        book_number, 
        bill_number
        FROM  rcpt_print
        WHERE vn = $vn AND `status` IS NULL";

//echo $sql;
$res = $mysql->selectOne($sql, null);

// print_r($res);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// $data['results'] = $res;
echo json_encode($res);

?>