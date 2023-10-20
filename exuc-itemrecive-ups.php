<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();

$data['vn'] = $_GET['vn'];
//$json = file_get_contents('php://input');
//$array = json_decode($json, true);

$sql = "SELECT
	opitemrece.icode AS 'icode',
	opitemrece.income AS 'INCOME',
	opitemrece.vstdate AS 'vstdate',
	income.`name` AS 'INCOME_NAME',
	rbh_excu_itemrecive.status_item AS 'status_item',
IF
	( nondrugitems.NAME IS NULL OR nondrugitems.NAME = '', '', nondrugitems.NAME ) AS 'NondrugName',
IF
	(
		drugitems.NAME IS NULL 
		OR drugitems.NAME = '',
		'',
	CONCAT( drugitems.`name`, ' (', drugitems.strength, ' ) ' )) AS 'DrugName',
IF
	( drugitems.units IS NULL OR drugitems.units = '', '-', drugitems.units ) AS 'DrugUnits',
IF
	(
		drugusage.name1 IS NULL 
		OR drugusage.name1 = '',
		'-',
		concat( drugusage.name1, drugusage.name2, drugusage.name3 ) 
	) AS 'DrugUsage',
	opitemrece.unitprice AS 'UnitPrice',
	opitemrece.qty AS 'Qty',
	ROUND( (opitemrece.unitprice * opitemrece.qty), 2 ) AS 'Total',
	opitemrece.doctor AS 'DoctorCode',
	doc.`name` AS 'DOC_NEW',
	doc.`code` AS 'DOC_NEWCODE',
IF
	( doctor.NAME IS NULL OR doctor.NAME = '', '-', doctor.NAME ) AS 'DoctorName',
	opitemrece.hn AS 'HN',
	opitemrece.vn AS 'VN' ,
	false AS 'select'

        
	
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
    LEFT OUTER JOIN rbh_excu_itemrecive ON rbh_excu_itemrecive.icode = opitemrece.icode and rbh_excu_itemrecive.vn = opitemrece.vn
	Left OUTER JOIN ovst ov ON ov.vn=opitemrece.vn
    Left outer join doctor doc ON doc.`code`= ov.doctor
WHERE
	opitemrece.vn = :vn 
  	
	AND rbh_excu_itemrecive.status_item = 1
GROUP BY 
	rbh_excu_itemrecive.icode 
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
