<?php
class Results extends Database
{
    public $tests = array();

    public function fetchAllResults($user_id)
    {
        $tests = $this->fetchTests();
        if (count($tests) > 0) {
            foreach ($tests as $test) {
                $newTest = $test;
                $newTest["results"] = array();
                $newTest["results"] = $this->fetchSingleTestResults($test["id"], $user_id);
                array_push($this->tests, $newTest);
            }
        }
    }

    private function fetchTests()
    {
        $data = array();
        $query = "SELECT id, title FROM tests WHERE `status` = 'open'";
        $sql = $this->conn->prepare($query);
        $sql->execute();
        $result = $sql->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
        }
        return $data;
    }

    private function fetchSingleTestResults($test_id, $user_id)
    {
        $data = array();
        $query = "SELECT id, table_name, create_date FROM test_results WHERE `test_id` = ? AND `user_id` = ? AND `status` = 'open' ORDER BY create_date DESC";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("ii", $test_id, $user_id);
        $sql->execute();
        $result = $sql->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
        }
        return $data;
    }
}
