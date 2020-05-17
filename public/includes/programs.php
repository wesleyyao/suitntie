<?php
    class ProgramData extends Database {
        public $id = 0;
        public $title = "";
        public $description = "";
        public $status = "";
        public $categoryId = 0;
        public $courses = array();
        public $info = array();
        public $books = array();
        public $related_programs = array();
        public $child_programs = array();
        public $testimonials = array();

        public function fetch_program_data(){
            $data = array();
            $categories = $this->fetch_category();
            if(count($categories) > 0){
                foreach($categories as $key){
                    $obj = $key;
                    $obj["details"] = array();
                    $obj["details"] = $this->fetch_programs_by_cateogry($obj["id"]);
                    array_push($data, $obj);
                }
            }
            return $data;
        }

        public function fetch_program_data_all(){
            $data = array();
            $categories = $this->fetch_all_program_cateogories();
            if(count($categories) > 0){
                foreach($categories as $key){
                    $obj = $key;
                    $obj["details"] = array();
                    $obj["details"] = $this->fetch_programs_by_cateogry($obj["id"]);
                    array_push($data, $obj);
                }
            }
            return $data;
        }

        public function fetch_program_details($title){
            $data = array();
            $this->fetch_program($title);
            $query_course = "SELECT `id`, `name`, `content`, `item_index` FROM program_course WHERE `status` = 'open' AND p_id = ?";
            $this->courses = $this->fetch_data_by_foreign_key($query_course, $this->id);
            $query_info = "SELECT `id`, `content`, `p_index`, `type` FROM program_info WHERE `status` = 'open' AND p_id = ?";
            $this->info = $this->fetch_data_by_foreign_key($query_info, $this->id);
            $query_books = "SELECT `id`, `title`, `author`, `douban`, `image`, `link` FROM self_learn_recommend WHERE `status` = 'open' AND p_id = ?";
            $this->books = $this->fetch_data_by_foreign_key($query_books, $this->id);
            $this->related_programs = $this->fetch_programs_by_cateogry($this->categoryId);
            $query_child = "SELECT `id`, `name`, `content`, `item_index` FROM child_program WHERE `status` = 'open' AND p_id = ?";
            $this->child_programs = $this->fetch_data_by_foreign_key($query_child, $this->id);
            $query_testimonials = "SELECT `id`, `name`, `feedback`, `school`, `program`, `grade` FROM program_testimonials WHERE `status` = 'open' AND p_id = ?";
            $this->testimonials = $this->fetch_data_by_foreign_key($query_testimonials, $this->id);
        }

        public function fetch_category(){
            $data = array();
            $query = "SELECT `id`, `name`, `image`, `status` FROM program_categories WHERE `status` = 'open'";
            $sql = $this->conn->prepare($query);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        private function fetch_programs_by_cateogry($pc_id){
            $data = array();
            $query = "SELECT `id`, `title`, `description` FROM programs WHERE `status` = 'open' AND pc_id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $pc_id);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function fetch_all_program_items(){
            $data = array();
            $query = "SELECT `id`, `title`, `description`, `pc_id`, `status` FROM programs";
            $sql = $this->conn->prepare($query);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        private function fetch_program($title){
            $data;
            $query = "SELECT `id`, `title`, `description`, `pc_id` FROM programs WHERE `status` = 'open' AND title = ? LIMIT 1";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $title);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                $data = mysqli_fetch_assoc($result);
                $this->id = $data["id"];
                $this->title = $data["title"];
                $this->description = $data["description"];
                $this->categoryId = $data["pc_id"];
            }
        }

        private function fetch_all_program_cateogories(){
            $data = array();
            $query = "SELECT `id`, `name`, `image`, `status` FROM program_categories";
            $sql = $this->conn->prepare($query);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function save_new_program_category($name, $imageUrl, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO program_categories (`name`, `image`, `status`) VALUES (?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sss", $name, $imageUrl, $status);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_program_category($name, $imageUrl, $status, $id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "";

            if(empty($imageUrl)){
                $query = "UPDATE program_categories SET `name` = ?, `status` = ? WHERE id = ?";
            }
            else{
                $query = "UPDATE program_categories SET `name` = ?, `image` = ?, `status` = ? WHERE id = ?";
            }
            try {
                $sql = $this->conn->prepare($query);
                if(empty($imageUrl)){
                    $sql->bind_param("ssi", $name, $status, $id);
                }
                else{
                    $sql->bind_param("sssi", $name, $imageUrl, $status, $id);
                }
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_program($name, $desc, $status, $pc_id, $id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE programs SET `title` = ?, `description` = ?, `status` = ?, `pc_id` = ? WHERE `id` = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssii", $name, $desc, $status, $pc_id, $id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_program($name, $desc, $status, $pc_id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "INSERT INTO programs (`title`, `description`, `status`, `pc_id`) VALUES (?,?,?,?)";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssi", $name, $desc, $status, $pc_id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }
    }
?>