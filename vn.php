<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();

$data['hn'] = $_GET['hn'];
//$json = file_get_contents('php://input');
//$array = json_decode($json, true);

$sql = "SELECT
	vs.vn AS 'VN',
	vs.hn AS 'HN',
	p.pname,
	p.fname,
	p.lname,
	p.birthday,
	vs.vstdate AS 'VisitDate',
	t.NAME AS 'PttypeName',
	rf.refer_date AS 'ReferDate',
	rf.refer_hospcode AS 'Hospcode',
	h.`name` AS 'oldname',
    exc.hospcode_origin AS 'Hospcode_origin',
    hexc.`name` AS 'Hos_newname', 
    (CASE WHEN vs.vn IN (SELECT vn FROM rbh_excu_itemrecive WHERE vn = vs.vn GROUP BY vn) THEN 1 ELSE 0 END) AS 'statusExcu'   ,
    (SELECT save_datetime FROM rbh_excu_itemrecive WHERE vn = vs.vn GROUP BY vn)  AS 'excu_datetime',
    (SELECT icd10 FROM ovstdiag AS od WHERE od.vn = vs.vn AND od.diagtype = 1 GROUP BY od.vn) AS 'icd10'

FROM
	vn_stat AS vs
	LEFT OUTER JOIN pttype t ON t.pttype = vs.pttype
	LEFT OUTER JOIN patient p ON p.hn = vs.hn 
	LEFT outer JOIN referin rf ON rf.vn = vs.vn
	LEFT OUTER JOIN hospcode h ON  h.hospcode = rf.refer_hospcode
	LEFT OUTER JOIN  rbh_excu_refer exc ON exc.vn=vs.vn
    LEFT OUTER JOIN  hospcode hexc ON hexc.hospcode=exc.hospcode_origin
WHERE
	vs.hn = :hn
	ORDER BY vs.vn DESC ";

$res = $mysql->selectAll($sql, $data);



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


echo json_encode($res);

?>
