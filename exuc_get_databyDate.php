<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();

$data['datetime'] = $_GET['datetime'];

$sql = "SELECT
	opitemrece.hn AS 'HN',
	rbh_excu_itemrecive.vn AS 'VN',
	opitemrece.vstdate AS 'vstdate',
	patient.cid AS 'cid',
	patient.fname AS 'fname',
	patient.lname AS 'lname',
	opitemrece.income AS 'INCOME',
	income.`name` AS 'INCOME_NAME',
	referin.refer_hospcode AS 'referin_hospcode',
	rbh_excu_itemrecive.icode AS 'icode',
	hospcode.NAME AS 'hospname',
IF
	( doctor.NAME IS NULL OR doctor.NAME = '', '-', doctor.NAME ) AS 'DoctorName',
IF
	( nondrugitems.NAME IS NULL OR nondrugitems.NAME = '', '', nondrugitems.NAME ) AS 'NondrugName',
IF
	(
		drugitems.NAME IS NULL 
		OR drugitems.NAME = '',
		'',
	CONCAT( drugitems.`name`, ' (', drugitems.strength, ' ) ' )) AS 'DrugName',
	opitemrece.unitprice AS 'UnitPrice' 
FROM
	patient
	LEFT OUTER JOIN opitemrece ON opitemrece.hn = patient.hn
	LEFT OUTER JOIN vn_stat ON vn_stat.vn = opitemrece.vn
	LEFT OUTER JOIN rbh_excu_itemrecive ON rbh_excu_itemrecive.vn = opitemrece.vn
	LEFT OUTER JOIN doctor ON doctor.`code` = opitemrece.doctor
	LEFT OUTER JOIN income ON income.income = opitemrece.income
	LEFT OUTER JOIN drugitems ON rbh_excu_itemrecive.icode = drugitems.icode
	LEFT OUTER JOIN nondrugitems ON rbh_excu_itemrecive.icode = nondrugitems.icode
	LEFT OUTER JOIN referin ON referin.vn = vn_stat.vn
	LEFT OUTER JOIN hospcode ON hospcode.hospcode = referin.refer_hospcode
	 
WHERE
	opitemrece.vstdate = :datetime
	AND rbh_excu_itemrecive.save_datetime IS NOT NULL 
	AND rbh_excu_itemrecive.status_item = '1' 
GROUP BY
	rbh_excu_itemrecive.icode";
$res = $mysql->selectAll($sql, $data);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


echo json_encode($res);

?>
