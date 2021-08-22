<?php
    require("../../utils/initial.php");
    require '../../vendor/autoload.php';
    require("../../public/includes/programs.php");
    require("../../public/includes/response.php");
    require("../../public/includes/ranking.php");
    require("../../public/includes/school.php");

    $programs = new ProgramData();
    $result = new ResponseData();
    $rankings = new Rankings();

    if(empty($_FILES["rankingTrackSheet"])){
        $result->code = 400;
        $result->message = "未收到上传文件。";
        echo json_encode($result);
        exit;
    }

    $uselessColumns = ["所属大类"];
    $fileName = $_FILES["rankingTrackSheet"]["name"];
    $targetDir = $_SERVER["DOCUMENT_ROOT"] . "$global_prefix/uploads/";
    $file = $targetDir . basename($_FILES["rankingTrackSheet"]["name"]);
    if(file_exists($file)){
        unlink($file);
    }
    $uploadResult = fileUpload($targetDir, "ranking", $_FILES["rankingTrackSheet"]);
    if($uploadResult["status"] != "failed"){
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $keys = ["A", "B", "C", "D"];
    
        $columnTitles = array();
        for($i = 0; $i < count($keys); $i++){
            $titleCode = $keys[$i] . "1";
            $columnTitle = $spreadsheet->getActiveSheet()->getCell($titleCode);
            if(!in_array(strtolower($columnTitle),$uselessColumns) && !empty($columnTitle)){
                $columnTitles[$keys[$i]] = $columnTitle;
            }
        }
        for($i = 2; $i < $spreadsheet->getActiveSheet()->getHighestRow() + 1; $i++){
            $originProgramTitle = "";
            $rankingProgramTitle = "";
            $origin = "";
            foreach($columnTitles as $key=>$value){
                if($value == "适途专业名称"){
                    $originProgramTitle = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else if($value == "来源"){
                    $origin = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else if($value == "专业名称"){
                    $rankingProgramTitle = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else{

                }
            }
        
            $isSaved = $programs->updateProgramRankingDataByName($origin, $rankingProgramTitle, $originProgramTitle);

            if(!$isSaved){
                $result->code = 500;
                $result->message = "记录保存失败。cell: ($i) | 适途专业: " . $originProgramTitle;
                $result->data = $columnTitles;
                echo json_encode($result);
                exit;
            }
        }
        $result->code = 200;
        $result->data = $columnTitles;
        $result->message = "$fileName 的所有条目保存成功。";
        echo json_encode($result);
        exit;
    }
    else{
        $result->code = 500;
        $result->message = $uploadResult["message"];
        echo json_encode($result);
        exit;
    }

?>