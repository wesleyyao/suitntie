<?php
    require("../../utils/initial.php");
    require("../../public/includes/initial.php");
    require '../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    if(!isset($_GET["target"])){
        redirect("$global_prefix/manager/index.php");
        exit;
    }
    $target = $_GET["target"];
    $data = array();
    if($target == "customers"){
        $data = $customer->fetch_all_users();
    }
    $column_length = count($data[0]);
    $title_index = 0;
    foreach($data[0] as $key  => $val){
        $sheet->setCellValue($columns[$title_index] . "1", $key);
        $title_index++;
    }
    $row_counter = 2;
    foreach($data as $item){
        $index = 0;
        foreach($item as $key  => $val){
            $sheet->setCellValue(($columns[$index] . $row_counter), $val);
            $index++;
        }
        $row_counter++;
    }
    $writer = new Xlsx($spreadsheet);
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment; filename="file.xlsx"');
    $writer->save("php://output");
    exit;
?>