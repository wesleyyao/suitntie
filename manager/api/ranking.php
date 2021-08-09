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
    $schools = new Schools();
    //use PhpOffice\PhpSpreadsheet\Spreadsheet;

    //$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $data = array();
        $data["programRankings"] = $rankings->fetchAllRankingData();
        $data["overallRankings"] = $schools->fetchAll();
        echo json_encode($data);
        exit;
    }

    if(empty($_FILES["rankingFile"])){
        $result->code = 400;
        $result->message = "未收到上传文件。";
        echo json_encode($result);
        exit;
    }
    $isOverallRanking = $_POST["isOverallRanking"];

    $uselessColumns = ["score"];
    $fileName = $_FILES["rankingFile"]["name"];
    $targetDir = $_SERVER["DOCUMENT_ROOT"] . "$global_prefix/uploads/";
    $file = $targetDir . basename($_FILES["rankingFile"]["name"]);
    if(file_exists($file)){
        unlink($file);
    }
    $uploadResult = fileUpload($targetDir, "ranking", $_FILES["rankingFile"]);
    if($uploadResult["status"] != "failed"){
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $keys = ["A", "B", "C", "D", "E", "F", "G", "H"];
        $columnArray = ['rank', 'university', '位置', 'logo_path', 'logo_url'];

        $columnTitles = array();
        for($i = 0; $i < count($keys); $i++){
            $titleCode = $keys[$i] . "1";
            $columnTitle = $spreadsheet->getActiveSheet()->getCell($titleCode);
            if(!in_array(strtolower($columnTitle),$uselessColumns) && !empty($columnTitle)){
                $columnTitles[$keys[$i]] = $columnTitle;
            }
        }
        $foundProgramByFileName = null;
        if($isOverallRanking){
            $schools->removeSchools();
            $schools->reset_table_index("schools2");
        }
        else{
            // check if the program exists in program table
            $foundProgramByFileName = $programs->fetch_program(str_replace(".csv","",$fileName));
            if(!$foundProgramByFileName){
                $result->code = 500;
                $result->message = "该上传文件所指专业不存在数据库中。";
                echo json_encode($result);
                exit;
            }
            $isRankingDataRemoved = $rankings->removeRankingsByProgram($foundProgramByFileName["id"]);
            if(!$isRankingDataRemoved){
                $result->code = 500;
                $result->message = "未成功删除专业" . $foundProgramByFileName["title"] . "的相关排名数据。";
                echo json_encode($result);
                exit;
            }
        }
        for($i = 2; $i < $spreadsheet->getActiveSheet()->getHighestRow() + 1; $i++){
            $rank = "";
            $university = "";
            $country = "";
            $logoPath = "";
            $logoUrl = "";
            if($foundProgramByFileName != null){
                $id = strval($foundProgramByFileName["id"]) . "R" . $i;
            }
            foreach($columnTitles as $key=>$value){
                if($value == "rank"){
                    $rank = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else if($value == "university"){
                    $university = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else if($value == "位置"){
                    $country = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else if($value =="logo_path"){
                    $logoPath = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else if($value =="logo_url"){
                    $logoUrl = $spreadsheet->getActiveSheet()->getCell($key . strval($i));
                }
                else{

                }
            }
        
            $isSaved = false;
            if($isOverallRanking){
                $isSaved = $schools->saveData($rank, $university, $country, $logoPath);
            }
            else{
                $isSaved = $rankings->saveRanking($id, $rank, $university, $country, $logoPath, $logoUrl, $foundProgramByFileName["id"]);
            }

            if(!$isSaved){
                $result->code = 500;
                $result->message = "记录保存失败。cell: ($i) | program id: " . 
                $foundProgramByFileName["id"] . " | " . $spreadsheet->getActiveSheet()->getHighestRow();
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