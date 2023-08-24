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
$sql = "SELECT 
vns.vn,
vns.hn,
vns.vstdate,
p.cid,
CONCAT(p.pname,p.fname,'  ', p.lname) AS 'ptname',
(SELECT icd10 FROM ovstdiag AS od WHERE od.vn = vns.vn AND od.diagtype = 1) AS 'icd10',
referin.refer_hospcode AS 'hospcode_referin',
d.`name` AS 'doctorname',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '06') AS 'income06',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '07') AS 'income07',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '08') AS 'income08',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '09') AS 'income09',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '99') AS 'income99',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '02') AS 'income02',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '10') AS 'income10',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '13') AS 'income13',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '81') AS 'income81',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '14') AS 'income14',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '15') AS 'income15',
(SELECT SUM(o.sum_price) FROM opitemrece AS o INNER JOIN income AS i ON i.income = o.income WHERE o.vn = vns.vn AND i.income = '01') AS 'income01'
FROM rbh_excu_itemrecive AS e
INNER JOIN vn_stat AS vns ON vns.vn = e.vn
INNER JOIN income AS i ON i.income = e.income
INNER JOIN patient AS p ON p.hn = vns.hn
LEFT JOIN referin ON referin.vn = e.vn
LEFT JOIN doctor AS d ON d.`code` = e.doctorcode
WHERE vns.vstdate BETWEEN '$sdate' and '$edate'
    AND  referin.refer_hospcode='$hoscode'
GROUP BY vns.vn";
//echo $sql;

$res = $mysql->selectAll($sql,null);



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


echo json_encode($res);

?>