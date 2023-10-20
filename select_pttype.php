<?php
error_reporting(0);
include "conn/hosxpdata.php";
$mysql = new hosxpdata();


$txt = '%'.$_REQUEST['query'].'%';

$sql = "SELECT pttype as 'id', CONCAT(pttype, ':', name) AS 'text' from pttype where (NAME like '$txt' OR pttype like '$txt') AND pcode IN ('U0','U1') ";
$res = $mysql->selectAll($sql, null);


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data['results'] = $res;
echo json_encode($data);

?><?php
