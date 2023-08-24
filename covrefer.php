<?php

include "conn/pdorbh.php";
include "conn/mydate.php";
require "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$mydate = new mydate();
$mysql = new pdorbh();

$dateinput = $_GET['dateinput'];
$hospcode = $_GET['hospcode'];
$refer = $_GET['refer'];

if ($refer == 1) {
    $refername = 'ส่งแพทย์ประเมินความเสี่ยง';
} else if ($refer == 2) {
    $refername = 'เข้ารับการรักษาจุด Home Isolation';
} else if ($refer == 3) {
    $refername = 'เข้ารับการรักษาจุด Self Isolation';
} else if ($refer == 4) {
    $refername = 'เข้ารับการรักษาจุด CI (เรือนจำกลาง)';
} else if ($refer == 5) {
    $refername = 'เข้ารับการรักษาจุด CI (เรือนจำกลางเขาบิน)';
} else {
    $refername = null;
}
$thdate = $mydate->entoth($dateinput, 'long');

//echo $refername;
$sql = "SELECT c.cid, c.pname,c.fname,c.lname,c.age,c.contact_number,
       c.number_address, c.moo, a.tmbname,c.sarstype,c.sarsdate,
       CASE c.vac1 WHEN 2 THEN 'SV' WHEN 3 THEN 'AZ' WHEN 4 THEN 'SP' WHEN 5 THEN 'PZ'WHEN 6 THEN 'MN' END AS vac1,
       CASE c.vac2 WHEN 2 THEN 'SV' WHEN 3 THEN 'AZ' WHEN 4 THEN 'SP' WHEN 5 THEN 'PZ'WHEN 6 THEN 'MN' END AS vac2,
       CASE c.vac3 WHEN 2 THEN 'SV' WHEN 3 THEN 'AZ' WHEN 4 THEN 'SP' WHEN 5 THEN 'PZ'WHEN 6 THEN 'MN' END AS vac3,
       CASE c.vac4 WHEN 2 THEN 'SV' WHEN 3 THEN 'AZ' WHEN 4 THEN 'SP' WHEN 5 THEN 'PZ' WHEN 6 THEN 'MN' END AS vac4,
        c.copd, c.ckd, c.cad, c.cva, c.udm, c.pids, c.liver_disease, c.other_diseases, c.weight, c.high, c.bmi,
        c.fever, c.cough, c.sorethroat, c.musclepain, c.mucous, c.phlegm, c.difficulbreathing, c.headache,
        c.purify, c.smell, c.taste, c.redeye, c.rash, c.symptom, h.hospname
        FROM covrefer AS c INNER JOIN rb_dhph AS h ON c.hospcode = h.hospcode
        LEFT JOIN address AS a  ON c.chwpart = a.chwpart AND c.amppart = a.amppart AND c.tmbpart = a.tmbpart
        WHERE  DATE ( datetimerecord ) = '$dateinput' AND c.hospcode = '$hospcode' AND refer = '$refer' ORDER BY datetimerecord ASC";

$result = $mysql->selectAll($sql);

$title1 = "แบบฟอร์มสำรวจการประเมินผู้ป่วย COVID-19 " . $refername;
$title2 = $result[0]['hospname'] . ' วันที่ ' . $thdate;


$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load(__DIR__ . '/template/covrefer.xlsx');

$baseRow = 5;
$r = 0;
$row = 0;

$spreadsheet->getActiveSheet()->mergeCells('A1:V1');
$spreadsheet->getActiveSheet()->setCellValue('A1', $title1);
$spreadsheet->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true)->setSize(10);
$spreadsheet->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->mergeCells('A2:V2');
$spreadsheet->getActiveSheet()->setCellValue('A2', $title2);
$spreadsheet->getActiveSheet()->getStyle('A2:V2')->getFont()->setBold(true)->setSize(10);
$spreadsheet->getActiveSheet()->getStyle('A2:V2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('D:F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$spreadsheet->getActiveSheet()->getStyle('R:S')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode('#############');

foreach ($result as $value) {
    $diseases = '';
    $symtom = '';

    $diseases = ($value['copd'] != 1 ? null : 'COPD ') . ($value['ckd'] != 1 ? null : 'CKD ') .
        ($value['cad'] != 1 ? null : 'CAD ') .
        ($value['cvd'] != 1 ? null : 'CVA ') .
        ($value['udm'] != 1 ? null : 'DM ') .
        ($value['pids'] != 1 ? null : 'ภูมิคุ้มกันบกพร่อง ') .
        ($value['liver_disease'] != 1 ? null : 'โรคตับ ') .
        ($value['other_diseases'] == null ? null : $value['other_diseases']);

    $symtom = ($value['fever'] == 1 ? 'ไข้ ' : null) .
        ($value['cough'] == 1 ? 'ไอ ' : null) .
        ($value['sorethroat'] == 1 ? 'เจ็บคอ ' : null) .
        ($value['musclepain'] == 1 ? 'ปวดกล้ามเนื้อ ' : null) .
        ($value['mucous'] == 1 ? 'มีน้ำมูก ' : null) .
        ($value['phlegm'] == 1 ? 'มีเสมหะ ' : null) .
        ($value['difficulbreathing'] == 1 ? 'หายใจลำบาก ' : null) .
        ($value['headache'] == 1 ? 'ปวดศีรษะ ' : null) .
        ($value['purify'] == 1 ? 'ถ่ายเหลว ' : null) .
        ($value['smell'] == 1 ? 'จมูกไม่ได้กลิ่น ' : null) .
        ($value['taste'] == 1 ? 'ลิ้นไม่รับรส ' : null) .
        ($value['redeye'] == 1 ? 'ตาแดง ' : null) .
        ($value['rash'] == 1 ? 'ผื่น ' : null) .
        ($value['symptom'] != null ? $value['symptom'] : null);

    $thsarsdate = $mydate->entoth($value['sarsdate'], 'short');

    $row = $baseRow + $r;
    $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);
    $spreadsheet->getActiveSheet()
        ->setCellValue('A' . $row, $r + 1)
        ->setCellValue('B' . $row, $value['cid'])
        ->setCellValue('D' . $row, $value['pname'])
        ->setCellValue('E' . $row, $value['fname'])
        ->setCellValue('F' . $row, $value['lname'])
        ->setCellValue('G' . $row, $value['age'])
        ->setCellValue('H' . $row, $value['number_address'])
        ->setCellValue('I' . $row, $value['moo'])
        ->setCellValue('J' . $row, $value['tmbname'])
        ->setCellValue('K' . $row, $value['contact_number'])
        ->setCellValue('L' . $row, $value['sarstype'])
        ->setCellValue('M' . $row, $thsarsdate)
        ->setCellValue('N' . $row, $value['vac1'])
        ->setCellValue('O' . $row, $value['vac2'])
        ->setCellValue('P' . $row, $value['vac3'])
        ->setCellValue('Q' . $row, $value['vac4'])
        ->setCellValue('R' . $row, $diseases)
        ->setCellValue('S' . $row, $symtom)
        ->setCellValue('T' . $row, $value['weight'])
        ->setCellValue('U' . $row, $value['high'])
        ->setCellValue('V' . $row, $value['bmi']);
    $r++;
}

$spreadsheet->getActiveSheet()->removeRow($row + 1, 1);
$spreadsheet->getActiveSheet()->getStyle('A3:V3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A4:V4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$writer = new Xlsx($spreadsheet);
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="covrefer.xlsx"');
$writer->save('php://output');


