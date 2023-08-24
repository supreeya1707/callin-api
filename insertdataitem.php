<?php
//error_reporting(1);
include "conn/hosxpdata.php";
$mysql = new hosxpdata();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$array = json_decode($json, true);

$VN             =   $array['VN'];
$HN             =   $array['HN'];
$pname          =   $array['pname'];
$fname          =   $array['fname'];
$lname          =   $array['lname'];
$visitdate      =   $array['visitdate'];
$pttype         =   $array['pttype'];
$referdate      =   $array['referdate'];
$hospcode_refer =   $array['hospcode_referin'];
$referinname    =   $array['referin_name'];
$NondrugName    =   $array['NonDrugname'];
$drugName       =   $array['Drugname'];
$Drugunit       =   $array['Drugunit'];
$DrugUsage      =   $array['DrugUsage'];
$Unitprice      =   $array['UnitPrice'];
$Qty            =   $array['Qty'];
$item_recive    =   $array['item_recive'];
$total_recive   =   $array['Total_recive'];
$item_cutoff    =   $array['item_cutoff'];
$total_cutoff   =   $array['Total_cutoff'];
$income         =   $array['INCOME'];
$income_name    =   $array['INCOME_name'];
$icode          =   $array['icode'];
$status_item    =   $array['status_item'];
$list_item      =   $array['list_item'];
$group_cost     =   $array['group_cost'];
$price_total    =   $array['price_total'];
$datetimefromsave =  $array['datetimefromsave'];
$Doctorcode     =   $array['Doctorcode'];
$DoctorName     =   $array['DoctorName'];


$sql = "INSERT INTO rbh_excu_itemrecive (VN, HN, pname, fname, lname, visitdate, pttype, referdate, hospcode_referin, referin_name, NonDrugname, DrugName, DrugUnit, DrugUsage, 
                                 UnitPrice, Qty, item_recive,Total_recive, item_cutoff, Total_cutoff, INCOME, INCOME_name, 
                                 icode, status_item, list_item, group_cost, price_total, datetimefromsave, Doctorcode, DoctorName) 
VALUES ('$VN', '$HN', '$pname', '$fname', '$lname', '$visitdate', '$pttype', '$referdate',  '$hospcode_refer', '$referinname', '$NondrugName', '$drugName', '$Drugunit', '$DrugUsage', 
        '$Unitprice', '$Qty', '$item_recive', '$total_recive', '$item_cutoff', '$total_cutoff', '$income', '$income_name',
        '$icode', 'Y', '$list_item', '$group_cost', '$price_total', NOW(), '$Doctorcode', '$DoctorName')";

//echo $sql;

$res = $mysql->insertData($sql, null);


if ($res == 1){
    $array0['status'] = 'Y';
}else{
    $array0['status'] = 'N';
}

echo json_encode($array0);



?>
