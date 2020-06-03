<?php
    class ProgramData extends Database {
        public $id = 0;
        public $title = "";
        public $description = "";
        public $related = "";
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
            $program_data = $this->fetch_program($title);
            if($program_data){
                $this->id = $program_data["id"];
                $this->title = $program_data["title"];
                $this->description = $program_data["description"];
                $this->categoryId = $program_data["pc_id"];
                $this->related = $program_data["related"];
            }
            $query_course = "SELECT `id`, `name`, `content`, `item_index`, `status`, `p_id` FROM program_course WHERE `status` = 'open' AND p_id = ?";
            $this->courses = $this->fetch_data_by_foreign_key($query_course, $this->id);
            $query_info = "SELECT `id`, `content`, `p_index`, `type`, `status`, `p_id` FROM program_info WHERE `status` = 'open' AND p_id = ?";
            $this->info = $this->fetch_data_by_foreign_key($query_info, $this->id);
            $query_books = "SELECT `id`, `title`, `author`, `douban`, `image`, `link`, `status`, `p_id`, `item_index` FROM self_learn_recommend WHERE `status` = 'open' AND p_id = ?";
            $this->books = $this->fetch_data_by_foreign_key($query_books, $this->id);
            $query_books_content = "SELECT `id`, `title`, `is_link`, `image`, `url` FROM self_learn_recommend_content WHERE `status` = 'open' AND parent_id = ?";
            $recommends = array();
            if(count($this->books) > 0){
                foreach($this->books as $item){
                    $rec_obj = $item;
                    $rec_obj["content"] = $this->fetch_data_by_foreign_key($query_books_content, $item["id"]);
                    array_push($recommends, $rec_obj);
                }
                $this->books = $recommends;
            }
            //$this->related_programs = $this->fetch_programs_by_cateogry($this->categoryId);
            $query_child = "SELECT `id`, `name`, `content`, `item_index`, `status`, `p_id` FROM child_program WHERE `status` = 'open' AND p_id = ?";
            $this->child_programs = $this->fetch_data_by_foreign_key($query_child, $this->id);
            $query_testimonials = "SELECT `id`, `name`, `feedback`, `school`, `program`, `grade`, `status`, `p_id` FROM program_testimonials WHERE `status` = 'open' AND p_id = ?";
            $this->testimonials = $this->fetch_data_by_foreign_key($query_testimonials, $this->id);
            if(!empty(trim($this->related))){
                $found_related = explode(" ", $this->related);
                foreach($found_related as $item){
                    $related_item = $this->fetch_program($item);
                    if($related_item){
                        array_push($this->related_programs, $related_item );
                    }
                }
            }
        }

        public function fetch_category(){
            $data = array();
            $query = "SELECT `id`, `name`, `image`, `item_index` `status` FROM program_categories WHERE `status` = 'open' ORDER BY item_index ASC";
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
            $query = "SELECT `id`, `title`, `description`, `pc_id`, `related`, `status` FROM programs ORDER BY id DESC";
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
            $query = "SELECT `id`, `title`, `description`, `pc_id`, `related` FROM programs WHERE `status` = 'open' AND title = ? LIMIT 1";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $title);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                return mysqli_fetch_assoc($result);
            }
            else {
                return false;
            }
        }

        private function fetch_all_program_cateogories(){
            $data = array();
            $query = "SELECT `id`, `name`, `image`, `item_index`, `status` FROM program_categories";
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

        public function save_new_program_category($name, $imageUrl, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO program_categories (`name`, `image`, `item_index`, `status`) VALUES (?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssis", $name, $imageUrl, $index, $status);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_program_category($name, $imageUrl, $index, $status, $id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "";

            if(empty($imageUrl)){
                $query = "UPDATE program_categories SET `name` = ?, `item_index`= ?, `status` = ? WHERE id = ?";
            }
            else{
                $query = "UPDATE program_categories SET `name` = ?, `image` = ?, `item_index` = ?, `status` = ? WHERE id = ?";
            }
            try {
                $sql = $this->conn->prepare($query);
                if(empty($imageUrl)){
                    $sql->bind_param("sisi", $name, $index, $status, $id);
                }
                else{
                    $sql->bind_param("ssisi", $name, $imageUrl, $index, $status, $id);
                }
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function fetch_program_details_manager($title){
            $data = array();
            $program_data = $this->fetch_program($title);
            if($program_data){
                $this->id = $program_data["id"];
                $this->title = $program_data["title"];
                $this->description = $program_data["description"];
                $this->categoryId = $program_data["pc_id"];
                $this->related = $program_data["related"];
            }
            $query_course = "SELECT `id`, `name`, `content`, `item_index`, `status`, `p_id` FROM program_course WHERE p_id = ?";
            $this->courses = $this->fetch_data_by_foreign_key($query_course, $this->id);
            $query_info = "SELECT `id`, `content`, `p_index`, `type`, `status`, `p_id` FROM program_info WHERE p_id = ?";
            $this->info = $this->fetch_data_by_foreign_key($query_info, $this->id);
            $query_books = "SELECT `id`, `title`, `author`, `douban`, `image`, `link`, `status`, `p_id`, `channel`, `online_course`, `item_index` FROM self_learn_recommend WHERE p_id = ?";
            $this->books = $this->fetch_data_by_foreign_key($query_books, $this->id);
            //$this->related_programs = $this->fetch_programs_by_cateogry($this->categoryId);
            $query_child = "SELECT `id`, `name`, `content`, `item_index`, `status`, `p_id` FROM child_program WHERE p_id = ?";
            $this->child_programs = $this->fetch_data_by_foreign_key($query_child, $this->id);
            $query_testimonials = "SELECT `id`, `name`, `feedback`, `school`, `program`, `grade`, `status`, `p_id` FROM program_testimonials WHERE p_id = ?";
            $this->testimonials = $this->fetch_data_by_foreign_key($query_testimonials, $this->id);
            if(!empty(trim($this->related))){
                $found_related = explode(" ", $this->related);
                foreach($found_related as $item){
                    $related_item = $this->fetch_program($item);
                    if($related_item){
                        array_push($this->related_programs, $related_item );
                    }
                }
            }
        }

        public function update_program($name, $desc, $status, $related, $pc_id, $id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE programs SET `title` = ?, `description` = ?, `status` = ?, `related` = ?, `pc_id` = ? WHERE `id` = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssssii", $name, $desc, $status, $related, $pc_id, $id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_program($name, $desc, $status, $related, $pc_id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "INSERT INTO programs (`title`, `description`, `status`, `related`, `pc_id`) VALUES (?,?,?,?,?)";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssssi", $name, $desc, $status, $related, $pc_id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function fetch_program_course_by_id($id){
            $query = "SELECT `id`, `name`, `content`, `item_index`, `status` FROM program_course WHERE `status` = 'open' AND id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $id);
            $sql->execute();
            $result = $sql->get_result();
            return mysqli_fetch_assoc($result);
        }

        public function update_program_course($id, $name, $content, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE program_course SET `name` = ?, `content` = ?, `item_index` = ?, `status` = ? WHERE id = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssisi", $name, $content, $index, $status, $id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_program_course($program_id, $name, $content, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "INSERT INTO program_course (`name`, `content`, `item_index`, `status`, `p_id`) VALUES (?, ?, ?, ?, ?)";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssisi", $name, $content, $index, $status, $program_id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function fetch_program_data_by_id($data, $id){
            $query = "";
            if($data == "info"){
                $query = "SELECT `id`, `content`, `p_index`, `type`, `status`, `p_id` FROM program_info WHERE id = ?";
            }
            else if($data == "course"){
                $query = "SELECT `id`, `name`, `content`, `item_index`, `status` FROM program_course WHERE `status` = 'open' AND id = ?";
            }
            else if($data == "book"){
                $query = "SELECT `id`, `title`, `author`, `douban`, `image`, `link`, `status`, `p_id`, `channel`, `online_course`, `item_index` FROM self_learn_recommend WHERE id = ?";
            }
            else if($data == "child"){
                $query = "SELECT `id`, `name`, `content`, `item_index`, `status`, `p_id` FROM child_program WHERE id = ?";
            }
            else{
                $query = "SELECT `id`, `name`, `feedback`, `school`, `program`, `grade`, `status`, `p_id` FROM program_testimonials WHERE id = ?";
            }
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $id);
            $sql->execute();
            $result = $sql->get_result();
            return mysqli_fetch_assoc($result);
        }

        public function save_program_info($program_id, $content, $type, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "INSERT INTO program_info (`content`, `type`, `p_index`, `status`, `p_id`) VALUES (?, ?, ?, ?, ?)";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssisi", $content, $type, $index, $status, $program_id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_program_info($id, $content, $type, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE program_info SET `content` = ?, `type` = ?, `p_index` = ?, `status` = ? WHERE id = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssisi", $content, $type, $index, $status, $id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_program_testimonial($program_id, $name, $content, $school, $program, $grade, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "INSERT INTO program_testimonials (`name`, `feedback`, `school`, `program`, `grade`, `status`, `p_id`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssssssi", $name, $content, $school, $program, $grade, $status, $program_id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_program_testimonial($id, $name, $content, $school, $program, $grade, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE program_testimonials SET `name` = ?, `feedback` = ?, `school` = ?, `program` = ?, `grade` = ?, `status` = ? WHERE id = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssssssi", $name, $content, $school, $program, $grade, $status, $id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_program_recommendation($program_id, $title, $imageUrl, $author, $douban, $link, $channel, $online_course, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "INSERT INTO self_learn_recommend (`title`, `image`, `author`, `douban`, `link`, `channel`, `online_course`, `item_index`, `status`, `p_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssssssisi", $title, $imageUrl, $author, $douban, $link, $channel, $online_course, $index, $status, $program_id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_program_recommendation($id, $title, $imageUrl, $author, $douban, $link, $channel, $online_course, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE self_learn_recommend SET `title` = ?, `image` = ?, `author` = ?, `douban` = ?, `link` = ?, `channel` = ?, `online_course` = ?, `item_index` = ?, `status` = ? WHERE id = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssssssisi", $title, $imageUrl, $author, $douban, $link, $channel, $online_course, $index, $status, $id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_program_children($program_id, $title, $content, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "INSERT INTO child_program (`name`, `content`, `item_index`, `status`, `p_id`) VALUES (?, ?, ?, ?, ?)";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssisi", $title, $content, $index, $status, $program_id);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_program_children($id, $title, $content, $index, $status){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE child_program SET `name` = ?, `content` = ?, `item_index` = ?,`status` = ? WHERE id = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssisi", $title, $content, $index, $status, $id);
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