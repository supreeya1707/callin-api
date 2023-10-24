<?php
error_reporting(0);
include "conn/hosxpdata.php";
$mysql = new hosxpdata();


$sql = "SELECT pttype as 'id', CONCAT(pttype, ' : ', name) AS 'text' from pttype WHERE  pcode IN ('U0','U1') ORDER BY pttype ASC";
$res = $mysql->selectAll($sql, null);


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


echo json_encode($res);

?><?php
