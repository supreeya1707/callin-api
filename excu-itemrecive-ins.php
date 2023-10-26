<?php
//error_reporting(1);
include "conn/hosxpdata.php";
$mysql = new hosxpdata();
$json = file_get_contents('php://input');
$array = json_decode($json, true);

//$sqlSelect= "select * from rbh_excu_itemrecive ";
$sqlSelect= "select * from rbh_excu_itemrecive where vn= ".$array['vn']." and icode = ".$array['icode']." ";
$resSelect = $mysql->selectAll($sqlSelect, null);
//print_r($array);
//exit();
//echo json_encode($resSelect);






//echo $sqlSelect;

if($resSelect){
    $arrayUpdate['id'] = $resSelect[0]['id'];
    $arrayUpdate['vn'] = $array['vn'];
    $arrayUpdate['icode'] = $array['icode'];
    $arrayUpdate['status_item'] = $array['status_item'];
    $arrayUpdate['update_datetime'] = $array['update_datetime'];
    $arrayUpdate['income'] = $array['income'];
    $arrayUpdate['vstdate'] = $array['vstdate'];
    $arrayUpdate['doctorcode'] = $array['doctorcode'];
    $arrayUpdate['login'] = $array['login'];

    $sqlUpdate = "UPDATE rbh_excu_itemrecive 
                    SET vn = :vn, 
                        icode = :icode, 
                        status_item = :status_item,  
                        update_login = :login,
                        update_datetime = :update_datetime,
                        income = :income,
                        doctorcode = :doctorcode,
                        vstdate= :vstdate 
                    WHERE id = :id";

    $res = $mysql->updateData($sqlUpdate, $arrayUpdate);
    if ($res){
        $array0['status'] = 'UY';
        $array0['res'] = $res;

    }else{
        $array0['status'] = 'UN';
        $array0['res'] = $res;
    }
}else{

//    $arrayins['vn'] = $array['vn'];
//    $arrayins['icode'] = $array['icode'];
//    $arrayins['status_item'] = $array['status_item'];
//    $arrayins['save_datetime'] = $array['save_datetime'];
//    $arrayins['update_datetime'] = $array['update_datetime'];
//    $arrayins['income'] = $array['income'];
//    $arrayins['vstdate'] = $array['vstdate'];
//    $arrayins['doctorcode'] = $array['doctorcode'];
    $sqlInsert = "INSERT INTO rbh_excu_itemrecive (vn, icode, vstdate, status_item, save_datetime, income, doctorcode, login) 
                    VALUES (:vn, :icode, :vstdate, :status_item, :save_datetime, :income, :doctorcode, :login)";
    $res = $mysql->insertData($sqlInsert, $array);
//    echo $sqlInsert;
    if ($res){
        $array0['status'] = "Y";
//        $array0['status'] = $res;
    }else{
        $array0['status'] = "N";
//        $array0['status'] = $res;

    }
}



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: 'http://www.rbhportal.com'");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
//header("Access-Control-Max-Age: 3600");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
echo json_encode($array0);



?>
