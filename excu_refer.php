<?php
include "conn/hosxpdata.php";
$mysql = new hosxpdata();
$json = file_get_contents('php://input');
$array = json_decode($json, true);

$sqlSelect= "select id from rbh_excu_refer where vn = '$array[vn]' ";
$resSelect = $mysql->selectOne($sqlSelect, null);
print_r($array);

//echo json_encode($resSelect);






//echo $sql;

if($resSelect){
//    echo "มีข้อมููล";;
    $id = $resSelect['id'];
    $arrayUpdate['hospcode_origin'] = $array['hospcode_origin'];
    $arrayUpdate['save_datetime'] = $array['save_datetime'];


    $sqlUpdate = "UPDATE rbh_excu_refer SET hospcode_origin = :hospcode_origin , save_datetime=:save_datetime WHERE id = '$id'";

    $res = $mysql->updateData($sqlUpdate, $arrayUpdate);
    if ($res){
        $array0['status'] = 'UY';
        $array0['res'] = $res;

    }else{
        $array0['status'] = 'UN';
        $array0['res'] = $res;
    }
}else{
//    echo "ไม่มี";
    $sqlInsert = "INSERT INTO rbh_excu_refer (vn, hospcode_origin, save_datetime) VALUES (:vn, :hospcode_origin, :save_datetime)";
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



echo json_encode($array0);



?>

