<?php
//error_reporting(1);
include "conn/hosxpdata.php";
$mysql = new hosxpdata();
$json = file_get_contents('php://input');
$array = json_decode($json, true);

//$sqlSelect= "select * from rbh_excu_itemrecive ";
$sqlSelect= "select * from rbh_excu_itemrecive where vn= $array[vn] and icode = $array[icode] ";
$resSelect = $mysql->selectAll($sqlSelect, null);
//print_r($array);
//exit();
//echo json_encode($resSelect);






//echo $sql;

if($resSelect){
    $id = $resSelect[0]['id'];
    $arrayUpdate['vn'] = $array['vn'];
    $arrayUpdate['icode'] = $array['icode'];
    $arrayUpdate['status_item'] = $array['status_item'];
    $arrayUpdate['update_datetime'] = $array['update_datetime'];
    $arrayUpdate['income'] = $array['income'];
    $arrayUpdate['doctorcode'] = $array['doctorcode'];

    $sqlUpdate = "UPDATE rbh_excu_itemrecive SET vn = :vn, icode = :icode, status_item = :status_item,  update_datetime = :update_datetime ,income = :income ,doctorcode = :doctorcode  WHERE id = '$id'";

    $res = $mysql->updateData($sqlUpdate, $arrayUpdate);
    if ($res){
        $array0['status'] = 'UY';
        $array0['res'] = $res;

    }else{
        $array0['status'] = 'UN';
        $array0['res'] = $res;
    }
}else{
    $sqlInsert = "INSERT INTO rbh_excu_itemrecive (vn, icode, status_item, save_datetime,update_datetime,income,doctorcode) VALUES (:vn, :icode, :status_item, :save_datetime, :update_datetime, :income, :doctorcode)";
    $res = $mysql->insertData($sqlInsert, $array);

    if ($res){
        $array0['status'] = "Y";
    }else{
        $array0['status'] = "N";

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
