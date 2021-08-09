<?php
    require("../../utils/initial.php");
    require("../../public/includes/initial.php");
    require '../../vendor/autoload.php';
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    if(!isset($_SESSION["login_staff"])){
        redirect("$global_prefix/manager/index.html");
        exit;
    }
    $columns = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    if(!isset($_GET["target"])){
        redirect("$global_prefix/manager/index.html");
        exit;
    }
    $target = $_GET["target"];

    function my_array_unique($array, $keep_key_assoc = false){
        $duplicate_keys = array();
        $tmp = array();       
    
        foreach ($array as $key => $val){
            // convert objects to arrays, in_array() does not support objects
            if (is_object($val))
                $val = (array)$val;
    
            if (!in_array($val, $tmp))
                $tmp[] = $val;
            else
                $duplicate_keys[] = $key;
        }
    
        foreach ($duplicate_keys as $key)
            unset($array[$key]);
    
        return $keep_key_assoc ? $array : array_values($array);
    }
    $data = array();
    if($target == "customers"){
        $data = my_array_unique($customer->fetch_all_users());
    }
    $column_length = count($data[0]);
    $title_index = 0;
    foreach($data[0] as $key  => $val){
        if($key != 'headImg' && $key != 'unionId' && $key != 'temporary_link'){
            $sheet->setCellValue($columns[$title_index] . "1", $key);
            $title_index++;
        }
    }
    $row_counter = 2;
    foreach($data as $item){
        $index = 0;
        foreach($item as $key  => $val){
            if($key != 'headImg' && $key != 'unionId' && $key != 'temporary_link'){
                $sheet->setCellValue(($columns[$index] . $row_counter), $val);
                $index++;
            }
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