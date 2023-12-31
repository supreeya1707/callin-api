<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();

$data['vn'] = $_GET['vn'];
//$json = file_get_contents('php://input');
//$array = json_decode($json, true);

$sql = "SELECT

	opitemrece.icode AS 'ICODE',
	opitemrece.income AS 'INCOME',
	income.`name` AS 'INCOME_NAME',
	
	IF(nondrugitems.name IS NULL or nondrugitems.name = '', '',nondrugitems.name) AS 'NondrugName',
	IF(drugitems.name IS NULL or drugitems.name = '', '',CONCAT( drugitems.`name`, ' (', drugitems.strength, ' ) ' )) AS 'DrugName',
	IF(drugitems.units IS NULL or drugitems.units = '', '-',drugitems.units) AS 'DrugUnits',
	IF(drugusage.name1 IS NULL or drugusage.name1 = '', '-',concat( drugusage.name1, drugusage.name2, drugusage.name3) )AS 'DrugUsage',
	
	opitemrece.unitprice AS 'UnitPrice',
	opitemrece.qty AS 'Qty',
	opitemrece.sum_price AS 'Total',
	
	opitemrece.doctor AS 'DoctorCode',
	IF(doctor.name IS NULL or doctor.name = '', '-',doctor.name)AS 'DoctorName'
	
FROM

	opitemrece
	LEFT OUTER JOIN drugitems ON opitemrece.icode = drugitems.icode
	LEFT OUTER JOIN nondrugitems ON opitemrece.icode = nondrugitems.icode
	LEFT OUTER JOIN income ON income.income = opitemrece.income
	LEFT OUTER JOIN vn_stat ON vn_stat.vn = opitemrece.vn
	LEFT OUTER JOIN drugusage ON drugusage.drugusage = opitemrece.drugusage
	LEFT OUTER JOIN patient ON patient.hn = opitemrece.hn
	LEFT OUTER JOIN doctor ON doctor.`code` = opitemrece.doctor
	LEFT OUTER JOIN pttype ON pttype.pttype = opitemrece.pttype 
	
WHERE
	opitemrece.vn = :vn 
	
ORDER BY
	opitemrece.income ASC";
$res = $mysql->selectAll($sql, $data);



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


echo json_encode($res);

?>
