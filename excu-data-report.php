<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();
//$dateinput = $_GET['dateinput'];
$data['datetime'] = $_GET['datetime'];
$day= $_GET['datetime'];
//function dateswap( $day ) {
//    $datearray = explode("-",$day);
//    if (strlen($day) > 2) {
//        $datadate = $datearray[1]."-".$datearray[0];
//    }return $datadate;
//}


//$monthYear = dateswap($day);
$monthYear = substr($day,0,7);


$sdate = $monthYear."-01";
$edate = $monthYear."-31";
$hoscode=$_GET['hoscode'];
$sql="
SELECT
	vns.vn,
	vns.hn,
	vns.vstdate,
	p.cid,
	CONCAT( p.pname, p.fname, '  ', p.lname ) AS 'ptname',
	( SELECT icd10 FROM ovstdiag AS od WHERE od.vn = vns.vn AND od.diagtype = 1 ) AS 'icd10',
	rr.hospcode_origin AS 'hospcode_referin',
	d.`name` AS 'doctorname',
	d.licenseno,
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '06' )) AS 'group2',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '07' )) AS 'group3',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '08' )) AS 'group4',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '09', '93', '99' )) AS 'group5',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '11' )) AS 'group6',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '02' )) AS 'group8',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '10' )) AS 'group9',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '13' )) AS 'group11',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '03', '04', '05', '81', '82', '83', '84', '85', '87', '94', '95', '96', '97' )) AS 'group12',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
		AND i.income = '14' 
	) AS 'group13',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '12', '15', '16', '98' )) AS 'group14',
	(
	SELECT
		SUM( o.sum_price ) 
	FROM
		opitemrece AS o
		INNER JOIN income AS i ON i.income = o.income 
	WHERE
		o.vn = vns.vn 
	AND i.income IN ( '01', '51', '52' )) AS 'group16' 
FROM
	rbh_excu_itemrecive AS e
	INNER JOIN vn_stat AS vns ON vns.vn = e.vn
	LEFT OUTER JOIN income AS i ON i.income = e.income
	INNER JOIN patient AS p ON p.hn = vns.hn
	LEFT JOIN rbh_excu_refer AS rr ON rr.vn = e.vn
	LEFT JOIN doctor AS d ON d.`code` = e.doctorcode 
WHERE
	vns.vstdate BETWEEN '$sdate' and '$edate'
	AND rr.hospcode_origin = '$hoscode'
GROUP BY
	vns.vn
";

//$sql="
//SELECT
//vns.vn,
//vns.hn,
//vns.vstdate,
//p.cid,
//CONCAT(p.pname,p.fname,'  ', p.lname) AS 'ptname',
//(SELECT icd10 FROM ovstdiag AS od WHERE od.vn = vns.vn AND od.diagtype = 1) AS 'icd10',
//rr.hospcode_origin AS 'hospcode_referin',
//d.`name` AS 'doctorname',
//d.licenseno,
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('06')) AS 'incomegroup2',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('07')) AS 'incomegroup3',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('08')) AS 'incomegroup4',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('09','93','99')) AS 'incomegroup5',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('11')) AS 'incomegroup6',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('02')) AS 'incomegroup8',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('10')) AS 'incomegroup9',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('13')) AS 'incomegroup11',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('03','04','05','81','82','83','84','85','87','94','95','96','97')) AS 'incomegroup12',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '14') AS 'incomegroup13',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('12','15','16','98')) AS 'incomegroup14',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income in('01','51','52')) AS 'incomegroup16'
//FROM rbh_excu_itemrecive AS e
//INNER JOIN vn_stat AS vns ON vns.vn = e.vn
//INNER JOIN income AS i ON i.income = e.income
//INNER JOIN patient AS p ON p.hn = vns.hn
//LEFT JOIN referin ON referin.vn = e.vn
//left join rbh_excu_refer as rr on rr.vn=e.vn
//LEFT JOIN doctor AS d ON d.`code` = e.doctorcode
//
//
//WHERE
//-- e.vn='651005130206'
//vns.vstdate BETWEEN '$sdate' and '$edate'
//--     AND  referin.refer_hospcode=11276
//AND rr.hospcode_origin='$hoscode'
//GROUP BY e.vn
//";

//$sql = "SELECT
//vns.vn,
//vns.hn,
//vns.vstdate,
//p.cid,
//CONCAT(p.pname,p.fname,'  ', p.lname) AS 'ptname',
//(SELECT icd10 FROM ovstdiag AS od WHERE od.vn = vns.vn AND od.diagtype = 1) AS 'icd10',
//referin.refer_hospcode AS 'hospcode_referin',
//d.`name` AS 'doctorname',
//
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '06') AS 'income06',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '07') AS 'income07',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '08') AS 'income08',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '09') AS 'income09',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '99') AS 'income99',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '02') AS 'income02',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '10') AS 'income10',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '13') AS 'income13',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '81') AS 'income81',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '14') AS 'income14',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '15') AS 'income15',
//(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '01') AS 'income01'
//FROM rbh_excu_itemrecive AS e
//INNER JOIN vn_stat AS vns ON vns.vn = e.vn
//INNER JOIN income AS i ON i.income = e.income
//INNER JOIN patient AS p ON p.hn = vns.hn
//LEFT JOIN referin ON referin.vn = e.vn
//LEFT JOIN doctor AS d ON d.`code` = e.doctorcode
//
//
//WHERE vns.vstdate BETWEEN '$sdate' and '$edate'
//    AND  referin.refer_hospcode='$hoscode'
//GROUP BY vns.vn";

//echo $sql;

$res = $mysql->selectAll($sql,null);



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


echo json_encode($res);

?>