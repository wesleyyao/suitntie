<?php
class DimensionResult extends Database
{
    public $code;
    public $title;
    public $description;
    public $basicAnalysis;
    public $career;
    public $characteristics;
    public $jobs;
    public $programs;
    public $tags;
    public $weights;
    public $majorDimensions;
    public $img;

    public function fetchResult($result_id)
    {
        $dimension_data = $this->fetchDimensionCodes($result_id);
        if (count($dimension_data) < 1) {
            return;
        }
        $compare_group = array();
        $compare_group = array_map(array($this, 'getCompareGroup'), $dimension_data);
        $compare_group = array_unique($compare_group);
        $result_dimensions = array();
        $weights = array();
        foreach ($compare_group as $key => $group) {
            $found_group = array();
            foreach ($dimension_data as $item) {
                if ($item["compare_group"] == $group) {
                    array_push($found_group, $item);
                }
            }
            array_push($weights, $found_group);
            $code_group = array();
            foreach ($dimension_data as $dimension) {
                if ($dimension["compare_group"] === $group) {
                    array_push($code_group, $dimension);
                }
            }
            $code = array_reduce($code_group, array($this, 'getMaxWeight'));
            array_push($result_dimensions, $code);
        }
        $this->majorDimensions = $result_dimensions;
        $this->code = implode("", array_map(array($this, 'getResultCode'), $result_dimensions));
        $combination_data = $this->fetchDimensionCombinationData($this->code);
        $this->title = $combination_data["title"];
        $this->img = $combination_data["characterImg"];
        $this->description = $combination_data["description"];
        $this->basicAnalysis = $combination_data["basic_analysis"];
        $combination_id = $combination_data["id"];
        $this->characteristics = $this->fetchCharacteristics($combination_id);
        $this->jobs = $this->fetchJobs($combination_id);
        $this->tags = $this->fetchTags($combination_id);
        $this->career = $this->fetchCareer($combination_id);
        $this->programs = $this->fetchPrograms($combination_id);
        $this->weights = $weights;
    }

    private function getCompareGroup($data)
    {
        return $data["compare_group"];
    }

    private function getMaxWeight($current, $next)
    {
        return $current["total"] > $next["total"] ? $current : $next;
    }

    private function getResultCode($data)
    {
        return $data["code"];
    }

    private function fetchDimensionCodes($id)
    {
        $data = array();
        $query = "SELECT r.id, r.dimension_id, r.total, d.code, d.title, d.description, d.compare_group FROM dimension_test_result r INNER JOIN dimensions d ON d.id = r.dimension_id WHERE r.test_result_id = ?";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    private function fetchTags($dcId)
    {
        $data = array();
        $query = "SELECT t.id, t.name FROM dimension_tags t INNER JOIN dimension_combination_tags_junction j ON t.id = j.tag_id WHERE j.dc_id = ? AND t.status = 'open'";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $dcId);
        $sql->execute();
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    private function fetchPrograms($dcId)
    {
        $data = array();
        $query = "SELECT p.id, p.title, p.description, p.link FROM programs p INNER JOIN dimension_combination_program_junction j ON p.id = j.program_id WHERE j.dc_id = ?";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $dcId);
        $sql->execute();
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    private function fetchDimensionCombinationData($code)
    {
        $query = "SELECT `id`, `code`, `title`, `description`, `basic_analysis`, `characterImg` FROM dimension_combination WHERE `code` = ? LIMIT 1";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("s", $code);
        $sql->execute();
        $result = $sql->get_result();
        return mysqli_fetch_assoc($result);
    }

    private function fetchCareer($dcId)
    {
        $query = "SELECT `id`, `title`, `description` FROM career_analysis WHERE dc_id = ? LIMIT 1";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $dcId);
        $sql->execute();
        $result = $sql->get_result();
        return mysqli_fetch_assoc($result);
    }

    private function fetchCharacteristics($dcId)
    {
        $data = array();
        $query = "SELECT `id`, `summary`, `description`, `type` FROM characteristics WHERE dc_id = ? ORDER BY item_index ASC";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $dcId);
        $sql->execute();
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    private function fetchJobs($dcId)
    {
        $data = array();
        $query = "SELECT `id`, `title`, `description` FROM jobs WHERE dc_id = ? AND `status` = 'open' ORDER BY item_index ASC";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $dcId);
        $sql->execute();
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    private function fetchDimension($group)
    {
        $data = array();
        $query = "SELECT `id`, `code`, `title`, `description` FROM dimensions WHERE compare_group = ? AND `status` = 'open'";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $group);
        $sql->execute();
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function fetchNumberOfTestResults()
    {
        $query = "SELECT COUNT(*) as 'total' FROM test_results";
        $sql = $this->conn->prepare($query);
        $sql->execute();
        $result = $sql->get_result();
        return mysqli_fetch_assoc($result)["total"];
    }

    public function fetchNumberOfTestLastWeek()
    {
        $query = "SELECT COUNT(*) as 'total' FROM test_results WHERE create_date >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY AND create_date < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY";
        $sql = $this->conn->prepare($query);
        $sql->execute();
        $result = $sql->get_result();
        return mysqli_fetch_assoc($result)["total"];
    }

    public function fetchNumberOfTestLastMonth()
    {
        $query = "SELECT COUNT(*) as 'total' FROM test_results WHERE YEAR(create_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(create_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
        $sql = $this->conn->prepare($query);
        $sql->execute();
        $result = $sql->get_result();
        return mysqli_fetch_assoc($result)["total"];
    }
}
