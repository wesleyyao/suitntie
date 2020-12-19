<?php
class Consult extends Database
{
    public $id;
    public $full_name = "";
    public $nick_name = "";
    public $region = "";
    public $program = array();
    public $education = "";
    public $introduction = "";
    public $thumbnail = "";
    public $educations = ["本科", "硕士", "博士"];

    public function fetch_consultants_with_schools_data()
    {
        $data = array();
        $consultants = $this->fetch_consultants();
        if (count($consultants) > 0) {
            foreach ($consultants as $item) {
                $temp_obj = $item;
                $temp_obj["programs"] = $this->fetch_consultants_programs($item["id"]);
                array_push($data, $temp_obj);
            }
        }
        return $data;
    }

    public function fetch_consultants()
    {
        $data = array();
        $query = "SELECT `id`, `name`, `nick_name`, `education`, `introduction`, `thumbnail` FROM tutors WHERE `status` = 'open'";
        $sql = $this->conn->prepare($query);
        if (!$sql->execute()) {
            return [];
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function fetch_all_consultants()
    {
        $data = array();
        $query = "SELECT `id`, `name`, `nick_name`, `education`, `introduction`, `thumbnail`, `status` FROM tutors";
        $sql = $this->conn->prepare($query);
        if (!$sql->execute()) {
            return [];
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function fetch_consultant_by_id($id)
    {
        $query = "SELECT `id`, `name`, `nick_name`, `education`, `introduction`, `thumbnail`, `status` FROM tutors WHERE id = ?";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $id);
        if (!$sql->execute()) {
            return [];
        }
        $result = $sql->get_result();
        return mysqli_fetch_assoc($result);
    }

    public function fetch_consultant_program_by_id($id)
    {
        $data = array();
        $query = "SELECT * FROM tutor_program_junction WHERE tutor_id = ?";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $id);
        if (!$sql->execute()) {
            return [];
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function fetch_all_schools()
    {
        $data = array();
        $query = "SELECT `id`, `name`, `region`, `status` FROM schools";
        $sql = $this->conn->prepare($query);
        if (!$sql->execute()) {
            return false;
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function fetch_all_regions()
    {
        $data = array();
        $query = "SELECT `id`, `name`, `r_index`, `status` FROM tutor_region ORDER BY r_index ASC";
        $sql = $this->conn->prepare($query);
        if (!$sql->execute()) {
            return [];
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    private function fetch_consultants_programs($id)
    {
        $data = array();
        $query = "SELECT t.school_id, t.program_id, t.scholarship, t.education, t.id, p.title, p.description, p.related, s.name as 'school', s.region, r.name as 'region_name' FROM tutor_program_junction t INNER JOIN programs p ON p.id = t.program_id INNER JOIN schools s ON s.id = t.school_id INNER JOIN tutor_region r ON r.id = s.region WHERE t.tutor_id = ? AND t.status = 'open'";
        $sql = $this->conn->prepare($query);
        $sql->bind_param("i", $id);
        if (!$sql->execute()) {
            return [];
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    private function fetch_schools()
    {
        $data = array();
        $query = "SELECT `id`, `name`, `region` FROM schools WHERE `status` = 'open'";
        $sql = $this->conn->prepare($query);
        if (!$sql->execute()) {
            return false;
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function fetch_regions()
    {
        $data = array();
        $query = "SELECT `id`, `name` FROM tutor_region WHERE `status` = 'open' ORDER BY r_index ASC";
        $sql = $this->conn->prepare($query);
        if (!$sql->execute()) {
            return [];
        }
        $result = $sql->get_result();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function save_tutor($name, $nick_name, $education, $introduction, $thumbnail, $status)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "INSERT INTO tutors (`name`, `nick_name`, `education`, `introduction`, `thumbnail`, `status`) VALUES (?,?,?,?,?,?)";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("ssssss", $name, $nick_name, $education, $introduction, $thumbnail, $status);
            if ($sql->execute()) {
                //$new_id = $this->conn->insert_id;
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function update_tutor($name, $nick_name, $education, $introduction, $thumbnail, $status, $id)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "UPDATE tutors SET `name` = ?, `nick_name` = ?, `education` = ?, `introduction` = ?, `thumbnail` = ?, `status` = ? WHERE id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("ssssssi", $name, $nick_name, $education, $introduction, $thumbnail, $status, $id);
            if ($sql->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function save_school($name, $regionId, $status)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "INSERT INTO schools (`name`, `region`, `status`) VALUES (?,?,?)";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("sis", $name, $regionId, $status);
            if ($sql->execute()) {
                //$new_id = $this->conn->insert_id;
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function update_school($name, $regionId, $status, $id)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "UPDATE schools SET `name` = ?, `region` = ?, `status` = ? WHERE id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("sisi", $name, $regionId, $status, $id);
            if ($sql->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function save_region($name, $index, $status)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "INSERT INTO tutor_region (`name`, `r_index`, `status`) VALUES (?,?,?)";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("sis", $name, $index, $status);
            if ($sql->execute()) {
                //$new_id = $this->conn->insert_id;
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function update_region($name, $index, $status, $id)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "UPDATE tutor_region SET `name` = ?, `r_index` = ?, `status` = ? WHERE id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("sisi", $name, $index, $status, $id);
            if ($sql->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function save_consultant_program($program_id, $school_id, $education_name, $program_status, $scholarship, $tutor_id)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "INSERT INTO tutor_program_junction (`tutor_id`, `program_id`, `education`, `school_id`, `scholarship`, `status`) VALUES (?,?,?,?,?,?)";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("iisiss", $tutor_id, $program_id, $education_name, $school_id, $scholarship, $program_status);
            if ($sql->execute()) {
                //$new_id = $this->conn->insert_id;
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function update_consultant_program($program_id, $school_id, $education_name, $program_status, $scholarship, $id)
    {
        $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        try {
            $query = "UPDATE tutor_program_junction SET `program_id` = ?, `school_id` = ?, `education` = ?, `scholarship` = ?, `status` = ? WHERE id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("iisssi", $program_id, $school_id, $education_name, $scholarship, $program_status, $id);
            if ($sql->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
