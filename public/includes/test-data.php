<?php
    class Test extends Database {
        public $test;
        public $topics = array();
        public $types = array();
        private $questions = array();
        public $total = 0;

        public function fetchData($title){
            $this->fetchTest($title);
            $this->fetchQuestions($this->test["id"]);
            if(count($this->types) > 0 && count($this->questions) > 0){
                $questions = array();
                foreach($this->questions as $item){
                    $newQuestionItem = $item;
                    $newQuestionItem["answers"] = array();
                    $answers = $this->fetchAnswers($item["id"]);
                    foreach($answers as $answer){
                        array_push($newQuestionItem["answers"], $answer);
                    }
                    array_push($questions, $newQuestionItem);
                }
                $this->questions = $questions;
                $types = array();
                foreach($this->types as $type){
                    $newTypeItem = $type;
                    $newTypeItem["questions"] = array();
                    foreach($this->questions as $question){
                        if($question["type_id"] == $type["id"]){
                            array_push($newTypeItem["questions"], $question);
                        }
                    }
                    array_push($types, $newTypeItem);
                }
                $this->types = $types;
                $this->total = $this->countQuestions($this->test["id"]);
            }
        }


        private function fetchTest($title){
            $data;
            $query = "SELECT `id`, `title` FROM tests WHERE `status` = 'open' AND `title` = ? LIMIT 1";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $title);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                $data = mysqli_fetch_assoc($result);
            }
            $this->test = $data;
            return $data;
        }

        private function fetchTopics($testId){
            $data = array();
            $query = "SELECT `id`, `name` FROM question_topics WHERE `test_id` = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $testId);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            $this->topics = $data;
            return $data;
        }

        private function fetchQuestionTypeIdByTopic($testId){
            $data = array();
            $query = "SELECT DISTINCT(type_id) FROM questions WHERE `status` = 'open' AND `topic_id` = ?";

            $topics = $this->fetchTopics($testId);
            if(count($topics) > 0){
                foreach ($topics as $key => $value) {
                    $sql = $this->conn->prepare($query);
                    $sql->bind_param("i", $testId);
                    $sql->execute();
                    $result = $sql->get_result();
                    if($result){
                        while($row = $result->fetch_assoc()){
                            array_push($data, $row["type_id"]);
                        }
                    }
                }
            }
            $data = array_unique($data);
            /*shuffle the question types */
            //shuffle($data);
            $types = array();
            if(count($data) > 0){
                foreach ($data as $id) {
                    array_push($types, $this->fetchQuestionTypeData($id, $testId));
                }
            }
            $this->types = $types;
            return $types;
        }

        private function fetchQuestionTypeData($id, $testId){
            $data = array();
            $query = "SELECT `id`, `name`, `questions_per_page` FROM question_type WHERE `id` = ? AND `status` = 'open' AND test_id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("ii", $id, $testId);
            $sql->execute();
            $result = $sql->get_result();
            return mysqli_fetch_assoc($result);
        }

        private function fetchQuestionsByTopic($topicId){
            $query = "SELECT q.id, q.topic_id, q.subject, q.type_id FROM questions q INNER JOIN question_type t ON t.id = q.type_id  WHERE q.status = 'open' AND q.topic_id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $topicId);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($this->questions, $row);
                }
            }
        }

        private function fetchQuestions($testId){
            $data = array();
            $this->fetchQuestionTypeIdByTopic($testId);
            if(count($this->topics) > 0){
                foreach ($this->topics as $topic) {
                    $this->fetchQuestionsByTopic($topic["id"]);
                }
                shuffle($this->questions);
            }
        }

        private function fetchAnswers($questionId){
            $data = array();
            $query = "SELECT `id`, `question_id`, `subject`, `dimension_id` FROM answers WHERE `status` = 'open' AND `question_id` = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $questionId);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        private function countQuestions($testId){
            $query = "SELECT count(q.id) as 'total' FROM questions q INNER JOIN question_topics t ON t.id = q.topic_id WHERE t.test_id = ? and q.status = 'open'";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $testId);
            $sql->execute();
            $result = $sql->get_result();
            return mysqli_fetch_assoc($result)["total"];
        }

        public function saveResult($test_id, $table, $user_id, $dimension_ids, $dimension_check_times, $user_name, $user_age, $purpose, $create_date){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $this->checkIfTotalResultsAreFull($test_id, $user_id, $table);
                $sql1 = $this->conn->prepare("UPDATE customers SET full_name = ?, age = ?, is_study_aboard = ? WHERE id = ?");
                $sql1->bind_param("sisi", $user_name, $user_age, $purpose, $user_id);
                if(!$sql1->execute()){
                    $this->conn->rollback();
                    return false;
                }
                $sql = $this->conn->prepare("INSERT INTO test_results (`test_id`, `table_name`, `user_id`, `create_date`, `status`) VALUES (?,?,?,?,'open')");
                $sql->bind_param("isis", $test_id, $table, $user_id, $create_date);
                if(!$sql->execute()){
                    $this->conn->rollback();
                    return false;
                }
                $new_result_id = $this->conn->insert_id;
                $counter = 0;
                foreach($dimension_ids as $id){
                    $this->saveDimensionResult(intval($id), intval($dimension_check_times[$counter]), $new_result_id);
                    $counter++;
                }
                $this->conn->commit();
                return $new_result_id;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        private function saveDimensionResult($id, $value, $result_id){
            $sql = $this->conn->prepare("INSERT INTO dimension_test_result (dimension_id, total, test_result_id) VALUES (?, ?, ?)");
            $sql->bind_param("iii", $id, $value, $result_id);
            $sql->execute();
        }

        private function updateDimensionResult($id, $value, $result_id){
            $sql = $this->conn->prepare("UPDATE dimension_test_result SET dimension_id = ?, total = ? WHERE test_result_id = ?");
            $sql->bind_param("iii", $id, $value, $result_id);
            $sql->execute();
        }

        private function fetchResultId($code){
            $query = "SELECT id FROM dimension_combination WHERE code = ? LIMIT 1";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $code);
            $sql->execute();
            $result = $sql->get_result();
            return mysqli_fetch_assoc($result);
        }

        private function checkIfTotalResultsAreFull($test_id, $user_id, $table_name){
            $query = "SELECT COUNT(id) as 'total' FROM test_results WHERE `test_id` = ? AND `user_id` = ? AND `table_name` = ? AND `status` = 'open'";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("iis", $test_id, $user_id, $table_name);
            $sql->execute();
            $result = $sql->get_result();
            $data = mysqli_fetch_assoc($result);
            if($data != NULL && $data["total"] == 7){
                $result_id = $this->findTheEarliestResult($test_id, $user_id, $table_name);
                $this->closeTheLatestResult($result_id);
            } 
        }

        private function findTheEarliestResult($test_id, $user_id, $table_name){
            $query = "SELECT id FROM test_results WHERE `test_id` = ? AND `user_id` = ? AND `table_name` = ? AND `status` = 'close' ORDER BY create_date ASC LIMIT 1";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("iis", $test_id, $user_id, $table_name);
            $sql->execute();
            $result = $sql->get_result();
            $data = mysqli_fetch_assoc($result);
            return $data ? $data["id"] : 0;
        }

        private function closeTheLatestResult($result_id){
            $query = "UPDATE test_results SET `status` = 'close' WHERE id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $result_id);
            $sql->execute();
        }
    }
?>