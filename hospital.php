<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();

$sql = "SELECT
        hospcode AS 'HospitalCode',
        concat( hospcode.hosptype, ' ', hospcode.NAME ) AS HospitalName
        FROM hospcode";

//echo $sql;
$res = $mysql->selectOne($sql, null);

print_r($res);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//$data['results'] = $res;
//echo json_encode($res);

?>