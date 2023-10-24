<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();

$data['datetime'] = $_GET['datetime'];
$day= $_GET['datetime'];

$monthYear = substr($day,0,7);


$sdate = $monthYear."-01";
$edate = $monthYear."-31";
$hoscode=$_GET['hoscode'];
$pttype = $_GET['pttype'];

$sql = "SELECT
	o.vn,
	e.cid,
	e.hn,
	e.ptfullname,
	o.vstdate,
	e.icd10,
	e.hospcode_origin,
	d.licenseno,
	FORMAT(SUM( CASE WHEN i.income_group = '02' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group2',
	FORMAT(SUM( CASE WHEN i.income_group = '03' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group3',
	FORMAT(SUM( CASE WHEN i.income_group = '04' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group4',
	FORMAT(SUM( CASE WHEN i.income_group = '05' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group5',
	FORMAT(SUM( CASE WHEN i.income_group = '06' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group6',
	FORMAT(SUM( CASE WHEN i.income_group = '08' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group8',
	FORMAT(SUM( CASE WHEN i.income_group = '09' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group9',
	FORMAT(SUM( CASE WHEN i.income_group = '11' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group11',
	FORMAT(SUM( CASE WHEN i.income_group = '12' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group12',
	FORMAT(SUM( CASE WHEN i.income_group = '13' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group13',
	FORMAT(SUM( CASE WHEN i.income_group = '14' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group14',
	FORMAT(SUM( CASE WHEN i.income_group = '16' THEN (o.unitprice * o.qty) ELSE 0 END ),2) AS 'group16' 
FROM
	opitemrece AS o
	INNER JOIN income AS i ON i.income = o.income
	LEFT JOIN drugitems di ON di.icode = o.icode 
	LEFT JOIN ovst ov ON ov.vn = o.vn
	LEFT JOIN doctor d ON d.CODE = ov.doctor 
	INNER JOIN (
	SELECT
		p.cid,
		od.vn,
		od.hn,
		CONCAT( p.pname, p.fname, ' ', p.lname ) AS 'ptfullname',
		od.icd10,
		r.hospcode_origin 
	FROM
		ovstdiag AS od
		INNER JOIN patient AS p ON p.hn = od.hn
		INNER JOIN rbh_excu_refer AS r ON r.vn = od.vn 
	WHERE
		od.diagtype = 1 
		AND od.vstdate BETWEEN '$sdate' AND  '$edate'
		AND od.vn IN ( SELECT e.vn FROM rbh_excu_itemrecive e WHERE e.vstdate BETWEEN '$sdate' AND  '$edate'  AND e.status_item = 1 GROUP BY e.vn ) 
	) AS e ON e.vn = o.vn 
WHERE
	o.vstdate BETWEEN '$sdate' AND  '$edate'
	AND o.pttype = '$pttype' 
	AND e.hospcode_origin = '$hoscode' 
	AND o.icode IN (
	SELECT
		icode 
	FROM
		rbh_excu_itemrecive 
	WHERE
		rbh_excu_itemrecive.vn = o.vn 
		AND rbh_excu_itemrecive.status_item = 1 
		AND rbh_excu_itemrecive.vstdate BETWEEN '$sdate' AND  '$edate'
	) 
GROUP BY o.vn";


$res = $mysql->selectAll($sql,null);



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


echo json_encode($res);

?>